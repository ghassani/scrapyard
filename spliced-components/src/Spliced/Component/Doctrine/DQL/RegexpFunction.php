<?php
/*
 * This file is part of the Spliced Component package.
*
* (c) Spliced Media <http://www.splicedmedia.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Spliced\Component\Doctrine\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\Parser;

/**
 * RegexpFunction
 * 
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 */
class RegexpFunction extends FunctionNode
{
    /** @var */
    public $fieldName = null;
    
    /** @var */
    public $pattern = null;

    /**
     * {@inheritDoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER); 

        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->fieldName = $parser->StringPrimary(); 

        $parser->match(Lexer::T_COMMA); 

        $this->pattern = $parser->StringPrimary(); 

        $parser->match(Lexer::T_CLOSE_PARENTHESIS); 
    }
    
    /**
     * {@inheritDoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf('%s REGEXP %s',$this->fieldName->dispatch($sqlWalker),$this->pattern->dispatch($sqlWalker));
    }
}
