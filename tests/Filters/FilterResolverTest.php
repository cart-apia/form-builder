<?php

use \Cartapia\FormBuilder\Filters\Collection\Trim;

class FilterResolverTest extends FormBuilderTestCase
{
    /** @test */
    public function it_resolve_alias_based_filter()
    {
        $expected = \Cartapia\FormBuilder\Filters\FilterInterface::class;
        $resolver = $this->filtersResolver;
        $this->assertInstanceOf(
            $expected, $resolver::instance('Trim')
        );
    }

    /** @test */
    public function it_resolve_object_based_filter()
    {
        $expected  = \Cartapia\FormBuilder\Filters\FilterInterface::class;
        $filterObj = new Trim();
        $resolver  = $this->filtersResolver;

        $resolvedFilterObj = $resolver::instance($filterObj);

        $this->assertInstanceOf(
            $expected, $filterObj
        );

        $this->assertEquals(
            $filterObj, $resolvedFilterObj
        );
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_object_is_not_instance_of_filterinterface()
    {
        $this->expectException(\Cartapia\FormBuilder\Filters\Exception\InvalidInstanceException::class);

        $invalidFilterObj = new stdClass();
        $resolver = $this->filtersResolver;
        $resolver::instance($invalidFilterObj);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_filter_cant_be_resolved()
    {
        $this->expectException(\Cartapia\FormBuilder\Filters\Exception\UnableToResolveFilterException::class);

        $invalidFilterClass = "\\Test\\Not\\Existing\\Class\\";
        $resolver = $this->filtersResolver;
        $resolver::instance($invalidFilterClass);
    }
}