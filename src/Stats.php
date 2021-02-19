<?php 
namespace Concrete\Package\CoreStatistics\Src;

use Config;
use FileList;
use Page;
use PageList;
use UserList;
use Database;

class Stats
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }


    /**
     * Number of log entries.
     *
     * @return int
     */
    public function getTotalLogs()
    {
        return $this->db->fetchColumn("SELECT count(1) FROM Logs");
    }


    /**
     * Number of stacks, global areas included.
     * We don't multiply the number of stacks by the number of locales.
     *
     * @return int
     */
    public function getTotalStacks()
    {
        return $this->db->fetchColumn("SELECT count(DISTINCT(cID)) FROM Stacks");
    }


    /**
     * Number of blocks.
     *
     * - Fetch latest version
     * - Get number of blocks per version per cID
     * - SUM blocks of all collections
     *
     * @return int
     */
    public function getTotalBlocks()
    {
        return $this->db->fetchColumn("
        select sum(a.cnt) from (
            select count(cvb.cID) as cnt from CollectionVersionBlocks cvb
                left join (
                    select cID, max(cvID) as cvID from CollectionVersionBlocks group by cID	
                ) tmp ON tmp.cID = cvb.cID and tmp.cvID = cvb.cvID
            where cvb.cvID = tmp.cvID AND cvb.cID = tmp.cID
            group by cvb.cID
        ) as a");
    }


    /**
     * Number of pages.
     *
     * - Approved, or not approved.
     * - Dashboard pages are excluded.
     *
     * @return int
     */
    public function getTotalPages()
    {
        $pl = new PageList();
        $pl->ignorePermissions();
        return (int) $pl->getTotalResults();
    }


    /**
     * List of unapproved pages.
     *
     * array []['cID'] int
     * array []['name'] string
     * array []['cDateModified'] mysql datetime
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUnapprovedPages()
    {
        return $this->db->executeQuery("
        select cv.cID, cv.cvName as name, c.cDateModified from (
            select cv.cID, max(cv.cvID) as cvID from CollectionVersions as cv
            inner join Pages as p ON p.cID = cv.cID
            WHERE p.cIsSystemPage = 0 and p.cIsActive = 1 and p.uID > 0
            group by cv.cID
        ) tmp
        inner join CollectionVersions as cv ON cv.cID = tmp.cID AND cv.cvID = tmp.cvID 
        inner join Collections as c ON c.cID = cv.cID
        where cv.cvIsApproved = 0
        ")->fetchAll();
    }


    /**
     * Number of pages in Trash.
     *
     * @return int
     */
    public function getTotalPagesInTrash()
    {
        $total = 0;
        $trash = Page::getByPath(Config::get('concrete.paths.trash'));
        if ($trash && !$trash->isError()) {
            $total = $this->db->fetchColumn("
            select count(1) from Pages as p 
            inner join PagePaths as pp ON pp.cID = p.cID
            where p.cIsActive = 0 and pp.cPath LIKE '" . $trash->getCollectionPath() . "%'
            ");
        }

        return (int) $total;
    }


    /**
     * Number of pages in Drafts.
     *
     * @return int
     */
    public function getTotalPagesInDrafts()
    {
        $total = 0;
        $drafts = Page::getByPath(Config::get('concrete.paths.drafts'));
        if ($drafts && !$drafts->isError()) {
            $total = $this->db->fetchColumn("SELECT count(1) FROM Pages INNER JOIN Collections c ON Pages.cID = c.cID WHERE cParentID = ? ORDER BY cDateAdded DESC", array($drafts->getCollectionID()));
        }

        return (int) $total;
    }


    /**
     * Number of pages per Page Type.
     *
     * array[]['ptID'] int
     * array[]['ptName'] string
     * array[]['num_pages'] int
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTotalPagesPerPageType()
    {

        return $this->db->executeQuery("
        select p.ptID, pt.ptName, count(p.ptID) as num_pages from 
        (
	        select cID, ptID from Pages where cIsSystemPage = 0 and cIsActive = 1 and uID > 0
        ) p 
        left join PageTypes pt ON pt.ptID = p.ptID
        group by p.ptID
        order by num_pages desc
        ")->fetchAll();
    }


    /**
     * Number of pages per Page Template.
     *
     * array[]['pTemplateID'] int
     * array[]['pTemplateName'] string
     * array[]['num_pages'] int
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTotalPagesPerPageTemplate()
    {
        return $this->db->executeQuery("
        select cv.pTemplateID, pt.pTemplateName, count(cv.pTemplateID) as num_pages from CollectionVersions as cv
        inner join (
            select cv.cID, max(cv.cvID) as cvID from CollectionVersions as cv
            inner join Pages as p ON p.cID = cv.cID  and p.uID > 0
            WHERE p.cIsSystemPage = 0
            group by cv.cID
        ) tmp ON cv.cID = tmp.cID and cv.cvID = tmp.cvID
        inner join PageTemplates pt ON pt.pTemplateID = cv.pTemplateID
        group by cv.pTemplateID
        order by num_pages desc
        ")->fetchAll();
    }


    /**
     * Pages with most Collection Versions.
     *
     * array[]['cID'] int
     * array[]['cvName'] string
     * array[]['num_versions'] int
     *
     * @param int $limit
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getPagesWithMostVersions($limit = 5)
    {
        return $this->db->executeQuery("
        select cID, cvName, count(cvID) as num_versions from CollectionVersions
        group by cID
        order by num_versions desc
        limit :limit
        ", array('limit' => $limit), array('limit' => \PDO::PARAM_INT))->fetchAll();
    }


    /**
     * Number of users.
     *
     * @return int
     */
    public function getTotalUsers()
    {
        $pl = new UserList();
        return (int) $pl->getTotalResults();
    }


    /**
     * Number of files.
     *
     * @return int
     */
    public function getTotalFiles()
    {
        $pl = new FileList();
        $pl->ignorePermissions();
        return (int) $pl->getTotalResults();
    }


    /**
     * Number of file sets.
     *
     * A fresh installation starts with one private file set.
     *
     * @return int
     */
    public function getTotalFileSets()
    {
        return (int) $this->db->fetchColumn("SELECT count(1) FROM FileSets");
    }


    /**
     * Number of files per File Set.
     *
     * array[]['fsID'] int
     * array[]['fsName'] string
     * array[]['num_files'] int
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTotalFilesPerFileSet()
    {
        return $this->db->executeQuery("
        select tmp.fsID, tmp.fsName, count(tmp.fID) as num_files from (
            select fs.fsID, fs.fsName, fsf.fID From FileSetFiles as fsf
            inner join FileSets as fs on fs.fsID = fsf.fsID
        ) as tmp
        group by tmp.fsID
        order by num_files desc, fID desc
        ")->fetchAll();
    }


    /**
     * Total size of all files.
     *
     * Sums up the file size of the latest version per file.
     *
     * @return int
     */
    public function getTotalFileSize()
    {
        return (int) $this->db->fetchColumn("
        select sum(fv.fvSize) as total_size from FileVersions fv
        inner join (
            select fID, max(fvID) as fvID from FileVersions
            group by fID
        ) as tmp on tmp.fvID = fv.fvID and tmp.fID = fv.fID
        ");
    }


    /**
     * Largest files based on file size.
     *
     * @param int $limit
     * @return \File[]
     */
    public function getLargestFiles($limit = 10)
    {
        $fl = new FileList();
        $fl->sortBy('fvSize', 'desc');
        $fl->getQueryObject()->setMaxResults($limit);
        return $fl->getResults();
    }


    /**
     * Most downloaded files.
     *
     * array[]['fID'] int
     * array[]['num_downloads'] int
     *
     * @param int $limit
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getMostDownloadedFiles($limit = 10)
    {
        return $this->db->executeQuery("
        select ds.fID, count(ds.fvID) as num_downloads from DownloadStatistics as ds
        inner join FileVersions as fv ON fv.fID = ds.fID
        group by ds.fID
        order by num_downloads desc
        limit :limit
        ", array('limit' => $limit), array('limit' => \PDO::PARAM_INT))->fetchAll();
    }


    /**
     * Number of active users.
     *
     * @return int
     */
    public function getTotalActiveUsers()
    {
        return (int) $this->db->fetchColumn("select count(1) from Users where uIsActive = 1");
    }


    /**
     * Number of inactive users.
     *
     * @return int
     */
    public function getTotalInactiveUsers()
    {
        return (int) $this->db->fetchColumn("select count(1) from Users where uIsActive = 0");
    }


    /**
     * Number of validated users.
     *
     * @return int
     */
    public function getTotalValidatedUsers()
    {
        return (int) $this->db->fetchColumn("select count(1) from Users where uIsValidated = 1");
    }


    /**
     * Number of not validated users.
     * @return int
     */
    public function getTotalNotValidatedUsers()
    {
        return (int) $this->db->fetchColumn("select count(1) from Users where uIsValidated = 0");
    }


    /**
     * Number of users per group.
     *
     * array[]['gID'] int
     * array[]['gName'] string
     * array[]['num_users'] int
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTotalUsersPerGroup()
    {
        return $this->db->executeQuery("
        select tmp.gID, tmp.gName, count(tmp.uID) as num_users from (
            select g.gID, g.gName, u.uID from Users u
            inner join UserGroups ug
            on ug.uID = u.uID
            right join Groups g
            on ug.gID = g.gID
        ) tmp
        group by tmp.gID, tmp.gName
        order by num_users desc, tmp.gID desc
        ")->fetchAll();
    }
}
