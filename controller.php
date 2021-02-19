<?php 
namespace Concrete\Package\CoreStatistics;

use Package;
use Page;
use SinglePage;

class Controller extends Package
{
    protected $pkgHandle = 'core_statistics';
    protected $appVersionRequired = '5.7.1';
    protected $pkgVersion = '0.9.3';

    protected $single_pages = array(
        '/dashboard/system/environment/core_statistics' => array(
            'cName' => 'Core Statistics'
        )
    );

    public function getPackageName()
    {
        return t("Core Statistics");
    }

    public function getPackageDescription()
    {
        return t("Statistics about your concrete5 installation.");
    }


    public function install()
    {
        $pkg = parent::install();
        $this->installOrUpgrade($pkg);
    }


    public function upgrade()
    {
        parent::upgrade();
        $this->installOrUpgrade($this);
    }


    private function installOrUpgrade($pkg)
    {
        $this->installPages($pkg);
    }


    /**
     * @param Package $pkg
     * @return void
     */
    private function installPages($pkg)
    {
        foreach ($this->single_pages as $path => $value) {
            if (!is_array($value)) {
                $path = $value;
                $value = array();
            }
            $page = Page::getByPath($path);
            if (!$page || $page->isError()) {
                $single_page = SinglePage::add($path, $pkg);

                if ($value) {
                    $single_page->update($value);
                }
            }
        }
    }
}