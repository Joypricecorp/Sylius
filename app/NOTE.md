modify:

 - src/Sylius/Bundle/CoreBundle/Resources/config/services/fixtures_factories.xml
 - \Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder
 - Gulpfile
 
 install added
 
 ```bash
sf doctrine:database:drop --force --connection=media
sf doctrine:database:create --connection=media
sf doctrine:phpcr:init:dbal --force
sf doctrine:phpcr:repository:init

npm install && npm run gulp && npm run modernizr
```
