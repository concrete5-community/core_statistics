<?php   
namespace Concrete\Package\CoreStatistics\Controller\SinglePage\Dashboard\System\Environment;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Package\CoreStatistics\Src\Stats;

class CoreStatistics extends DashboardPageController
{
    protected $stats;
    protected $tab_active = false;


    public function on_before_render()
    {
        parent::on_before_render();

        $tabs       = array();
        $categories = array(
            'overview' => t('Overview'),
            'pages' => t('Pages'),
            'users' => t('Users'),
            'files' => t('Files')
        );

        foreach ($categories as $handle => $tabName) {
            $active = ($handle === $this->tab_active);
            $tabs[] = array($this->action($handle), $tabName, $active);
        }
        $this->set('tabs', $tabs);
    }


    /**
     * @param string|bool $handle
     * @return void
     */
    public function view($handle = false)
    {
        $this->stats      = new Stats();
        $this->tab_active = $handle;

        $func = 'getTab' . ucfirst($handle);
        if (method_exists($this, $func)) {
            $this->{$func}();
        } else {
            $this->redirect('dashboard/system/environment/core_statistics/overview');
        }
    }


    /**
     * Tab: Overview
     *
     * @return void
     */
    protected function getTabOverview()
    {
        $this->set('pages_total',     $this->stats->getTotalPages());
        $this->set('users_total',     $this->stats->getTotalUsers());
        $this->set('files_total',     $this->stats->getTotalFiles());
        $this->set('file_sets_total', $this->stats->getTotalFileSets());
        $this->set('logs_total',      $this->stats->getTotalLogs());
        $this->set('stacks_total',    $this->stats->getTotalStacks());
        $this->set('blocks_total',    $this->stats->getTotalBlocks());

        $this->render('dashboard/system/environment/core_statistics/overview');
    }


    /**
     * Tab: Pages
     *
     * @return void
     */
    protected function getTabPages()
    {
        $pages_total      = $this->stats->getTotalPages();
        $pages_unapproved = $this->stats->getUnapprovedPages();
        $pages_approved   = $pages_total - count($pages_unapproved);

        $this->set('pages_total', $pages_total);
        $this->set('pages_approved', $pages_approved);
        $this->set('pages_unapproved', $pages_unapproved);
        $this->set('pages_in_trash', $this->stats->getTotalPagesInTrash());
        $this->set('pages_in_drafts', $this->stats->getTotalPagesInDrafts());
        $this->set('page_types', $this->stats->getTotalPagesPerPageType());
        $this->set('page_templates', $this->stats->getTotalPagesPerPageTemplate());
        $this->set('pages_with_most_versions', $this->stats->getPagesWithMostVersions());

        $this->render('dashboard/system/environment/core_statistics/pages');
    }


    /**
     * Tab: Users
     *
     * @return void
     */
    protected function getTabUsers()
    {
        $this->set('users_total', $this->stats->getTotalUsers());
        $this->set('users_active', $this->stats->getTotalActiveUsers());
        $this->set('users_inactive', $this->stats->getTotalInactiveUsers());
        $this->set('users_validated', $this->stats->getTotalValidatedUsers());
        $this->set('users_not_validated', $this->stats->getTotalNotValidatedUsers());
        $this->set('groups', $this->stats->getTotalUsersPerGroup());

        $this->render('dashboard/system/environment/core_statistics/users');
    }


    /**
     * Tab: Files
     *
     * @return void
     */
    protected function getTabFiles()
    {
        $this->set('files_total', $this->stats->getTotalFiles());
        $this->set('file_sets_total', $this->stats->getTotalFileSets());
        $this->set('file_size_total', $this->stats->getTotalFileSize());
        $this->set('file_sets', $this->stats->getTotalFilesPerFileSet());
        $this->set('most_downloaded_files', $this->stats->getMostDownloadedFiles());
        $this->set('largest_files', $this->stats->getLargestFiles());

        $this->render('dashboard/system/environment/core_statistics/files');
    }
}