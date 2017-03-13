<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Eccube\Application;
use Eccube\Entity\PageLayout;
use Eccube\Entity\Master\DeviceType;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170312115644 extends AbstractMigration
{
    const PAGE_NAME = '商品問い合わせ';
    
    const URL = 'plg_product_contact_index';
    
    public function __construct()
    {
        $this->app = Application::getInstance();
    }
    
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->createPageLayout();

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->deletePageLayout();

    }
    
    protected function createPageLayout()
    {
        $em = $this->app['orm.em'];
        
        $DeviceType = $this->app['eccube.repository.master.device_type']
                ->find(DeviceType::DEVICE_TYPE_PC);
        
        $PageLayout = new PageLayout();
        $PageLayout->setDeviceType($DeviceType);
        $PageLayout->setName(self::PAGE_NAME);
        $PageLayout->setUrl(self::URL);
        $PageLayout->setEditFlg(PageLayout::EDIT_FLG_DEFAULT);
        $em->persist($PageLayout);
        $em->flush();
    }
    
    protected function deletePageLayout()
    {
        $DeviceType = $this->app['eccube.repository.master.device_type']
                ->find(DeviceType::DEVICE_TYPE_PC);
        
        $PageLayout = $this->findPageLayout($DeviceType, self::URL);
        if($PageLayout instanceof PageLayout) {
            $em->remove($PageLayout);
            $em->flush();
        }
    }
    
    protected function findPageLayout($DeviceType, $url)
    {
        try{
            $qb = $this->app['orm.em']->createQueryBuilder();
            $qb
                    ->from('Eccube\Entity\PageLayout', "pl")
                    ->where('pl.Device = :DeviceType AND pl.url = :Url')
                    ->setParameter("DeviceType", $DeviceType)
                    ->setParameter("Url", $url);
            return $qb->getSingleResult();
        } catch (\Exception $e) {
            return false;
        }
    }
}
