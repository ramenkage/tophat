<?php

/**
 * @file
 * Archives all Slack channels whose names begin with "p-".
 *
 * Run using <code>drush php:script archive_all_puzzle_channels</code>
 */

use JoliCode\Slack\ClientFactory;

$config = \Drupal::config('puzzlehunt.adminsettings');
$slack_client = ClientFactory::create($config->get('slack_token'));
$cursor = NULL;

do {
  $params = [
    'exclude_archived' => TRUE,
    'limit' => 1000,
  ];
  if ($cursor) {
    $params['cursor'] = $cursor;
    print '-- next page starting at ' . $cursor . " --\n";
  }

  $response = $slack_client->conversationsList($params);
  $channels = $response->getChannels();

  foreach ($channels as $channel) {
    $channel_id = $channel->getId();
    $channel_name = $channel->getName();

    print $channel_name . ' | ';

    if ($channel->getIsMember()) {
      print 'Member';
    }
    else {
      $slack_client->conversationsJoin(['channel' => $channel_id]);
      print 'Joined';
    }

    print ' | ';

    if (str_starts_with($channel_name, 'p-')) {
      $slack_client->conversationsArchive(['channel' => $channel_id]);
      print 'Archived';
      sleep(3);
    }
    else {
      print 'Skipped';
    }

    print "\n";
  }

  $cursor = $response->getResponseMetadata()->getNextCursor();
} while ($cursor);

print "-- done --\n";
