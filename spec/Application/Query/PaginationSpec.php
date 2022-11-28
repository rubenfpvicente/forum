<?php
/**
 * This file is part of forum
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\App\Application\Query;

use App\Application\Query\Pagination;
use PhpSpec\ObjectBehavior;

class PaginationSpec extends ObjectBehavior
{

    function its_initializable()
    {
        $this->shouldBeAnInstanceOf(Pagination::class);
    }

    function it_calculates_the_total_pages_for_a_result_set()
    {
        $this->beConstructedWith(2, 1, 25);
        $this->totalPages()->shouldBe((int) ceil(25/2));
    }

    function it_has_a_total_rows_per_page()
    {
        $this->rowsPerPage()->shouldBe(12);
    }

    function it_has_a_current_page()
    {
        $this->page()->shouldBe(1);
    }

    function it_has_a_total_rows()
    {
        $this->totalRows()->shouldBe(0);
    }

    function it_has_an_offset()
    {
        $this->beConstructedWith(2, 3, 25);
        $this->offset()->shouldBe(4);
    }

    function it_can_change_the_total_rows_value()
    {
        $pag = $this->withTotalRows(23);
        $pag->shouldBeAnInstanceOf(Pagination::class);
        $pag->shouldNotBe($this->getWrappedObject());
        $pag->totalPages()->shouldBe(2);

    }

    function it_can_be_converted_to_json()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBe([
            'rowsPerPage' => 12,
            'page' => 1,
            'totalRows' => 0,
            'offset' => 0,
            'totalPages' => 1
        ]);
    }
}
