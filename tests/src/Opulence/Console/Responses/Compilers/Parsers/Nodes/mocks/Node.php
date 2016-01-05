<?php
/**
 * Opulence
 *
 * @link      https://www.opulencephp.com
 * @copyright Copyright (C) 2016 David Young
 * @license   https://github.com/opulencephp/Opulence/blob/master/LICENSE.md
 */
namespace Opulence\Tests\Console\Responses\Compilers\Parsers\Nodes\Mocks;

use Opulence\Console\Responses\Compilers\Parsers\Nodes\Node as BaseNode;

/**
 * Mocks a node for use in testing
 */
class Node extends BaseNode
{
    /**
     * @inheritdoc
     */
    public function isTag()
    {
        return false;
    }
}