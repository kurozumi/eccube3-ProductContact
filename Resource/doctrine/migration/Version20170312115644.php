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
    
    /**
     * dtb_page_layoutにページ情報を登録
     */
    protected function createPageLayout()
    {
        $em = $this->app['orm.em'];
        
        $DeviceType = $this->app['eccube.repository.master.device_type']
                ->find(DeviceType::DEVICE_TYPE_PC);
        
        $PageLayout = $this->findPageLayout($DeviceType, self::URL);
        if($PageLayout === false) {
            $PageLayout = new PageLayout();
            $PageLayout->setDeviceType($DeviceType);
            $PageLayout->setName(self::PAGE_NAME);
            $PageLayout->setUrl(self::URL);
            $PageLayout->setEditFlg(PageLayout::EDIT_FLG_DEFAULT);
            $em->persist($PageLayout);
            $em->flush();
        } else {
            throw new \Exception(sprintf("%sはすでに登録されています。", self::URL));
        }
       
    }
    
    /**
     * dtb_page_layoutからページ情報を削除
     */
    protected function deletePageLayout()
    {
        $em = $this->app['orm.em'];
        
        $DeviceType = $this->app['eccube.repository.master.device_type']
                ->find(DeviceType::DEVICE_TYPE_PC);
        
        $PageLayout = $this->findPageLayout($DeviceType, self::URL);
        if($PageLayout instanceof PageLayout) {
            $em->remove($PageLayout);
            $em->flush();
        }
    }
    
    /**
     * ルーティングからページ情報を取得
     * 
     * @param type $DeviceType
     * @param type $url ルーティング
     * @return boolean
     */
    protected function findPageLayout($DeviceType, $url)
    {
        try{
            $em = $this->app['orm.em'];
            
            $PageLayout = $em->getRepository('Eccube\Entity\PageLayout');
            $PageLayout = $PageLayout->createQueryBuilder("pl")
                    ->where('pl.DeviceType = :DeviceType AND pl.url = :url')
                    ->setParameter("DeviceType", $DeviceType)
                    ->setParameter("url", $url)
                    ->getQuery()
                    ->getSingleResult();
            return $PageLayout;
        } catch (\Exception $e) {
            return false;
        }
    }
}
