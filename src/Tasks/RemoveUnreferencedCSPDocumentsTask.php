<?php

namespace Signify\Tasks;

use Signify\Jobs\RemoveUnreferencedCSPDocumentJob;
use SilverStripe\Dev\BuildTask;
use Symbiote\QueuedJobs\Services\QueuedJobService;
use Symfony\Component\Console\Input\InputInterface;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Command\Command;

class RemoveUnreferencedCSPDocumentsTask extends BuildTask
{
    protected string $title = 'Remove unreferenced CSP Document URIs';

    protected string $description =
        'CSP Document URIs that are not referenced by a CSP violation report can be safely removed.';

    /**
     * {@inheritDoc}
     * @see \SilverStripe\Dev\BuildTask::execute()
     */
    protected function execute(InputInterface $input, PolyOutput $output): int
    {
        $deletionJob = new RemoveUnreferencedCSPDocumentJob();

        $jobId = singleton(QueuedJobService::class)->queueJob($deletionJob);

        $output->writeln("Job queued with ID $jobId");

        return Command::SUCCESS;
    }

    public function isEnabled(): bool
    {
        return parent::isEnabled() && class_exists(QueuedJobService::class);
    }
}
