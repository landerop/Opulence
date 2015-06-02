<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Mocks the RDev Predis class for use in testing
 */
namespace RDev\Tests\Redis\Mocks;
use RDev\Redis\RDevPredis as BaseRDevPredis;
use RDev\Redis\Server;
use RDev\Redis\TypeMapper;

class RDevPredis extends BaseRDevPredis
{
    /**
     * {@inheritdoc}
     */
    public function __construct(Server $server, TypeMapper $typeMapper)
    {
        $this->server = $server;
        $this->typeMapper = $typeMapper;
    }

    /**
     * We don't want to close the connection because there wasn't one, so do nothing
     */
    public function __destruct()
    {
        // Do nothing
    }

    /**
     * {@inheritdoc}
     */
    public function select($database)
    {
        // Don't actually select the database in Redis
        $this->server->setDatabaseIndex($database);
    }
} 