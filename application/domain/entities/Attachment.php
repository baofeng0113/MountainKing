<?php

/**
 * Data Object for `table ws_attachments`
 *
 * @license Apache License 2.0
 *
 * @package Domain_Entity
 *
 * @author BaoFeng <baofeng@noteyun.com>
 */
class Domain_Entity_Attachment
{
    private $id = null;

    private $serverPath = null;
    private $serverHost = null;
    private $serverURI1 = null;
    private $serverURI2 = null;

    private $fileName = null;
    private $fileSize = null;
    private $fileType = null;
    private $sequence = null;

    private $description = null;
    private $createdTime = null;
    private $createdIp = null;
    private $createdUser = null;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    public function getFileType()
    {
        return $this->fileType;
    }

    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    public function getServerPath()
    {
        return $this->serverPath;
    }

    public function setServerPath($serverPath)
    {
        $this->serverPath = $serverPath;
    }

    public function getServerHost()
    {
        return $this->serverHost;
    }

    public function setServerHost($serverHost)
    {
        $this->serverHost = $serverHost;
    }

    public function getServerURI1()
    {
        return $this->serverURI1;
    }

    public function setServerURI1($serverURI1)
    {
        $this->serverURI1 = $serverURI1;
    }

    public function getServerURI2()
    {
        return $this->serverURI2;
    }

    public function setServerURI2($serverURI2)
    {
        $this->serverURI2 = $serverURI2;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }

    public function getCreatedIp()
    {
        return long2ip($this->createdIp);
    }

    public function setCreatedIp($createdIp)
    {
        if (is_numeric($createdIp))
            $this->createdIp = $createdIp;
        else
            $this->createdIp = sprintf("%u", ip2long($createdIp));
    }

    public function getCreatedUser()
    {
        return $this->createdUser;
    }

    public function setCreatedUser($createdUser)
    {
        $this->createdUser = $createdUser;
    }

    public function toArray()
    {
        $object2Array = array();
        if ($this->id !== null)
            $object2Array['id'] = $this->id;
        if ($this->fileName !== null)
            $object2Array['filename'] = $this->fileName;
        if ($this->fileSize !== null)
            $object2Array['filesize'] = $this->fileSize;
        if ($this->fileType !== null)
            $object2Array['filetype'] = $this->fileType;
        if ($this->sequence !== null)
            $object2Array['sequence'] = $this->sequence;
        if ($this->serverPath !== null)
            $object2Array['server_path'] = $this->serverPath;
        if ($this->serverHost !== null)
            $object2Array['server_host'] = $this->serverHost;
        if ($this->serverURI1 !== null)
            $object2Array['server_uri1'] = $this->serverURI1;
        if ($this->serverURI2 !== null)
            $object2Array['server_uri2'] = $this->serverURI2;
        if ($this->description !== null)
            $object2Array['description'] = $this->description;
        if ($this->createdTime !== null)
            $object2Array['created_time'] = $this->createdTime;
        if ($this->createdIp !== null)
            $object2Array['created_ip'] = $this->createdIp;
        if ($this->createdUser !== null)
            $object2Array['created_user'] = $this->createdUser;
        return $object2Array;
    }
}