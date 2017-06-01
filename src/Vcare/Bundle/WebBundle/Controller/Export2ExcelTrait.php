<?php

namespace Vcare\Bundle\WebBundle\Controller;

use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait Export2ExcelTrait
{
    /**
     * @param \DateTime|null $dateTime
     * @param string $format
     *
     * @return string
     */
    protected function getDate(\DateTime $dateTime = null, $format = 'Y-m-d')
    {
        if (!$dateTime) {
            return '';
        }

        return $dateTime->format($format);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function excelAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::INDEX);

        /** @var ResourceGridView $resources */
        $resources = $this->resourcesCollectionProvider->get($configuration, $this->repository);
        $resources->getData()->setMaxPerPage(10000);

        $excel = $this->get('phpexcel')->createPHPExcelObject();
        $configs = (array)$request->get('_excel');

        $excel
            ->getProperties()
            ->setCreator($configs['creator']);

        $worksheet = $excel->setActiveSheetIndex(0)->setTitle($configs['title']);
        $columns = $configs['columns'];
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($resources->getData() as $row => $resource) {
            $rowIndex = ++$row;
            foreach (array_keys($columns) as $col => $column) {
                $columnValue = $columns[$column];
                $data = $propertyAccessor->getValue($resource, $column);

                if (is_array($columnValue)) {
                    if (isset($columnValue[1])) {
                        $data = call_user_func_array([$this, $columnValue[0]], array_merge([$data], $columnValue[1], [$resource]));
                    } else {
                        $data = call_user_func_array([$this, $columnValue[0]], [$data, $resource]);
                    }
                }

                $worksheet->setCellValueByColumnAndRow($col, $rowIndex, $data);
            }
        }

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($excel, 'Excel5');

        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT, strtolower($configs['title']) . '.xls'
        );

        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
