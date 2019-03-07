<?php
require_once('VirusTotalApiV2.php');

/* Initialize the VirusTotalApi class. */
$api = new VirusTotalAPIV2('4fd24af52e5e02023157ee94fddc6614814c31646c8f82dd24b1da89dab24ac9');

/* Upload and scan a local file. */
//$result = $api->scanFile('Relativ-path-to-a-local-file-to-scan');
//$scanId = $api->getScanID($result); /* Can be used to check for the report later on. */
//$api->displayResult($result);

/* Get a file report. */
//$report = $api->getFileReport('Hash-of-a-file-to-check-for-a-report');
//$api->displayResult($report);
//print($api->getSubmissionDate($report) . '<br>');
//print($api->getReportPermalink($report, TRUE) . '<br>');

/* Scan an URL. */
$result = $api->scanURL('http://186.33.234.5');
$scanId = $api->getScanID($result); /* Can be used to check for the report later on. */
$api->displayResult($result);
switch($scanId) {
  case -99: // API limit exceeded
    // deal with it here – best by waiting a little before trying again :)
    break;
  case  -1: // an error occured
    // deal with it here
    break;
  case   0: // no results (yet) – but the file is already enqueued at VirusTotal
    $scan_id = $vt->getScanId();
    $json_response = $vt->getResponse();
    break;
  case   1: // results are available
    $json_response = $vt->getResponse();
    $report = $api->getURLReport('http://186.33.234.5');
$api->displayResult($report);
print($api->getTotalNumberOfChecks($report) . '<br>');
print($api->getNumberHits($report) . '<br>');
print($api->getReportPermalink($report, FALSE) . '<br>');
    break;
  default : // you should not reach this point if you've placed the break before :)
}
/* Get an URL report. */
$report = $api->getURLReport('http://186.33.234.5');
$api->displayResult($report);
print($api->getTotalNumberOfChecks($report) . '<br>');
print($api->getNumberHits($report) . '<br>');
print($api->getReportPermalink($report, FALSE) . '<br>');

/* Comment on a file. */
//$report = $api->makeComment('Hash-of-a-file-to-comment-on', 'Your-comment');
//$api->displayResult($report);

/* Comment on an URL. */
//$report = $api->makeComment('URL-to-comment-on', 'Your-comment');
//$api->displayResult($report);
?>