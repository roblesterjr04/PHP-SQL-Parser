<?php
/**
 * CreateIndexOptionsBuilder.php
 *
 * Builds index options part of a CREATE INDEX statement.
 *
 * PHP version 5
 *
 * LICENSE:
 * Copyright (c) 2010-2014 Justin Swanhart and André Rothe
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * @author    André Rothe <andre.rothe@phosco.info>
 * @copyright 2010-2014 Justin Swanhart and André Rothe
 * @license   http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 * @version   SVN: $Id$
 * 
 */

namespace Lester\SQLParser\builders;
use Lester\SQLParser\exceptions\UnableToCreateSQLException;

/**
 * This class implements the builder for the index options of a CREATE INDEX
 * statement. 
 * You can overwrite all functions to achieve another handling.
 *
 * @author  André Rothe <andre.rothe@phosco.info>
 * @license http://www.debian.org/misc/bsd.license  BSD License (3 Clause)
 *  
 */
class CreateIndexOptionsBuilder implements Builder {

    protected function buildIndexParser($parsed) {
        $builder = new IndexParserBuilder();
        return $builder->build($parsed);
    }

    protected function buildIndexSize($parsed) {
        $builder = new IndexSizeBuilder();
        return $builder->build($parsed);
    }

    protected function buildIndexType($parsed) {
        $builder = new IndexTypeBuilder();
        return $builder->build($parsed);
    }

    protected function buildIndexComment($parsed) {
        $builder = new IndexCommentBuilder();
        return $builder->build($parsed);
    }

    protected function buildIndexAlgorithm($parsed) {
        $builder = new IndexAlgorithmBuilder();
        return $builder->build($parsed);
    }

    protected function buildIndexLock($parsed) {
        $builder = new IndexLockBuilder();
        return $builder->build($parsed);
    }

    public function build(array $parsed) {
        if ($parsed['options'] === false) {
            return '';
        }
        $sql = '';
        foreach ($parsed['options'] as $k => $v) {
            $len = strlen($sql);
            $sql .= $this->buildIndexAlgorithm($v);
            $sql .= $this->buildIndexLock($v);
            $sql .= $this->buildIndexComment($v);
            $sql .= $this->buildIndexParser($v);
            $sql .= $this->buildIndexSize($v);
            $sql .= $this->buildIndexType($v);

            if ($len == strlen($sql)) {
                throw new UnableToCreateSQLException('CREATE INDEX options', $k, $v, 'expr_type');
            }

            $sql .= ' ';
        }
        return ' ' . substr($sql, 0, -1);
    }
}
?>
