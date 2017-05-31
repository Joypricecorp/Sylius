<?php

namespace Vcare\Bundle\WebBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;

class CustomerController extends ResourceController
{
    use Export2ExcelTrait;
    use ExcelRenderer\CustomerTrait;
}
