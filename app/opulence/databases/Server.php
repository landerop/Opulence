<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Defines a database server
 */
namespace Opulence\Databases;

class Server
{
    /** @var string The host of this server */
    protected $host = "";
    /** @var int|null The port this server listens on */
    protected $port;
    /** @var string The username to log in to the server */
    protected $username = "";
    /** @var string The password to log in to the server */
    protected $password = "";
    /** @var string The name of the database to connect to on the server */
    protected $databaseName = "";
    /** @var string The character set used by this server */
    protected $charset = "utf8";

    /**
     * @param string $host The server host
     * @param string $username The username to log in to the server
     * @param string $password The password to log in to the server
     * @param string $databaseName The name of the database to connect to
     * @param int $port The port of this server
     * @param string $charset The character set used by this server
     */
    public function __construct(
        $host = null,
        $username = null,
        $password = null,
        $databaseName = null,
        $port = null,
        $charset = null
    ) {
        if ($host !== null) {
            $this->setHost($host);
        }

        if ($username !== null) {
            $this->setUsername($username);
        }

        if ($password !== null) {
            $this->setPassword($password);
        }

        if ($databaseName !== null) {
            $this->setDatabaseName($databaseName);
        }

        if ($port !== null) {
            $this->setPort($port);
        }

        if ($charset !== null) {
            $this->setCharset($charset);
        }
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    /**
     * @param string $databaseName
     */
    public function setDatabaseName($databaseName)
    {
        $this->databaseName = $databaseName;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
} 