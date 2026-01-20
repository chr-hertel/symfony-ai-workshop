<?php

namespace App\Mcp;

use Mcp\Capability\Attribute\McpTool;
use Symfony\AI\Agent\Bridge\SimilaritySearch\SimilaritySearch;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class SearchTool
{
    public function __construct(
        #[Autowire(service: 'symfony_schedule_search')]
        private SimilaritySearch $scheduleSearch,
        #[Autowire(service: 'symfony_blog_search')]
        private SimilaritySearch $blogSearch,
    ) {
    }

    /**
     * @param string $query The search query
     */
    #[McpTool('search_conference_schedule', 'Searches the conference schedule for relevant sessions based on the query.')]
    public function searchConferenceSchedule(string $query): string
    {
        return ($this->scheduleSearch)($query);
    }

    /**
     * @param string $query The search query
     */
    #[McpTool('search_blog', 'Searches the blog for relevant articles based on the query.')]
    public function searchBlog(string $query): string
    {
        return ($this->blogSearch)($query);
    }
}
