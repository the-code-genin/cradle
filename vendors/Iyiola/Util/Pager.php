<?php
namespace Hola\Util;

/**
 * This class adds pagination functionality thereby decreasing the overall page load time and making the page easier to read
 */
class Pager
{	
	// The current page the user is viewing
	private $current_page;
	
	// The total number of pages for the records
	private $num_pages;
	
	// Stores the number of records
	private $num_records;
	
	// The max number of records per page
	private $records_per_page;
	
	public function __construct(int $num_records = 0, int $records_per_page = 10, int $current_page = null)
	{
		$this->num_records = $num_records;
		$this->records_per_page = ($records_per_page >= 1)? $records_per_page : 10;
		$this->num_pages = ceil($num_records / $this->records_per_page);
		
		if ($current_page < 1 | !$current_page | $this->num_pages == 0) {
			$this->current_page = 1;
		} else if ($current_page > $this->num_pages) {
			$this->current_page = $this->num_pages;
		} else {
			$this->current_page = $current_page;
		}
	}
	
	/**
	 * Returns the current page
	 */
	public function getCurrentPage(): int
	{
		return $this->current_page;
	}

	/**
	 * Returns the number of records per page
	 */
	public function getRecordsPerPage(): int
	{
		return $this->records_per_page;
	}
	
	/**
	 * Returns the number of records supplied to the pager object
	 */
	public function getNumRecords(): int
	{
		return $this->num_records;
	}

	/**
	 * Returns the number of pages contained in the records
	 */
	public function getNumPages(): int
	{
		return $this->num_pages;
	}
	
	/**
	 * Returns the record index at which to start fetching records
	 */
	public function getStartRow(): int
	{
		return ($this->current_page - 1) * $this->records_per_page;
	}
	
	/**
	 * Returns a bool based on if there is a next page
	 */
	public function hasNext(): bool
	{
		return $this->current_page < $this->num_pages;
	}
	
	/**
	 * Gets the number of the next page
	 */
	public function getNext(): int
	{
		if ($this->has_next()) {
			return $this->current_page + 1;
		} else {
			return $this->current_page;
		}
	}
	
	/**
	 * Returns a bool based on if there is a previous page
	 */
	public function hasPrevious(): int
	{
		return $this->current_page > 1;
	}
	
	/**
	 * Gets the number of the previous page
	 */
	public function getPrevious(): int
	{
		if ($this->hasPrevious()) {
			return $this->current_page - 1;
		} else {
			return $this->current_page;
		}
	}
	
	/**
	 * Returns an array with the page numbers of pages close to the current page
	 */
	public function getNeighbourPages($no_pages = 5): array
	{
		if ($this->num_pages <= 1) {
			return [];
		}
		
		// Create a scale
		if ($no_pages % 2 == 0 ) {
			$no_pages--;
		}
		$scale = (abs($no_pages) - 1) / 2;
		$neighbour_pages = [];
		
		// Generate the pages to the left
		if ($this->current_page - $scale > 1) {
			$farthest_previous_page = $this->current_page - $scale;
			if ($this->current_page + $scale > $this->num_pages) {
				$roll_over_pages = abs(($this->current_page + $scale) - $this->num_pages);
				$farthest_previous_page -= $roll_over_pages;
				if ($farthest_previous_page < 1) {
					$farthest_previous_page = 1;
				}
			}
		} else {
			$farthest_previous_page = 1;
		}

		for ($i = $this->current_page; $i >= $farthest_previous_page; $i--) {
			array_unshift($neighbour_pages, $i);
		}
		
		// Generate the pages to the right
		if ($this->current_page + $scale < $this->num_pages) {
			$farthest_next_page = $this->current_page + $scale;
			if ($this->current_page - $scale < 1) {
				$roll_over_pages = abs(($this->current_page - $scale) - 1);
				$farthest_next_page += $roll_over_pages;
				if ($farthest_next_page > $this->num_pages) {
					$farthest_next_page = $this->num_pages;
				}
			}
		} else {
			$farthest_next_page = $this->num_pages;
		}

		for ($i = $this->current_page + 1; $i <= $farthest_next_page; $i++) {
			array_push($neighbour_pages, $i);
		}
		
		return $neighbour_pages;
	}
}
