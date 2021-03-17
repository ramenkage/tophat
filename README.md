# tophat - Puzzle tracking software for Mystery Hunt team ☃
This Drupal website tracks rounds and puzzles for collaborating on puzzle hunts.
It is ***decidedly*** a work-in-progress. The code is messy, and way too much
functionality is still in the configuration, so installation on a new server
is very fiddly. That said, once it's set up, it works pretty well.

## Installation
Huge caveat: I have not attempted to reinstall from scratch recently. The steps
below should theoretically work, but this code is provided as-is, and I don't
have time to help debug. It is currently working correctly on ☃'s server, so
rest assured it is possible to install. 👍

1. Clone this git repo.
1. Run `composer install` in the root tophat directory. This will install
   dependencies, including various libraries into /vendor, as well as Drupal
   core and contrib modules into /web/core and /web/modules/contrib.
1. Create a database:
   https://www.drupal.org/docs/installing-drupal/step-3-create-a-database
1. Configure your web server to use /web as the docroot for your domain or
   subdomain.
1. Run the Drupal installer:
   https://www.drupal.org/docs/installing-drupal/step-5-run-the-installer
1. Perform installation cleanup:
   https://www.drupal.org/docs/installing-drupal/step-6-status-check
1. Run `drush cset system.site uuid 20ff619e-db71-46c9-8143-78d061f0dff2` in
   the root tophat directory.
1. Browse to /admin/config/user-interface/shortcut/manage/default/customize and
   delete all shortcuts.
1. Enable the config module (if it isn't already enabled).
1. Browse to /admin/config/development/configuration/full/import and import
   the .tar.gz file located in this git repo under /config.
1. Browse to /admin/config/system/site-information and fix your site details.
1. Browse to /admin/config/services/google_api_client/add and add your
   credentials with service="Drive API" and
   scope="https://www.googleapis.com/auth/drive". (It *must* have id=0 in the
   list.)
1. Browse to /admin/config/puzzlehunt and add your Slack App credentials.
1. Adjust the Slack URLs in these Views: hunt_overview, round_list, my_puzzles
1. Browse to /admin/structure/taxonomy/manage/puzzle_statuses/overview and add
   your desired list of puzzle statuses.
1. Browse to /node/add to start adding Hunts, Rounds, and Puzzles.
