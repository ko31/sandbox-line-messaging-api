<?php
ini_set('error_log', dirname(__FILE__) . '/error.log');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/LINEBotTiny.php';

$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
$dotenv->load();

$channelAccessToken = $_ENV['CHANNEL_SECRET'];
$channelSecret      = $_ENV['CHANNEL_ACCESS_TOKEN'];

$client = new LINEBotTiny( $channelAccessToken, $channelSecret );
foreach ( $client->parseEvents() as $event ) {
	switch ( $event['type'] ) {
		case 'message':
			$message = $event['message'];
			switch ( $message['type'] ) {
				case 'text':
					$client->replyMessage( [
						'replyToken' => $event['replyToken'],
						'messages'   => [
							[
								'type' => 'text',
								'text' => $message['text']
							]
						]
					] );
					break;
				default:
					error_log( 'Unsupported message type: ' . $message['type'] );
					break;
			}
			break;
		default:
			error_log( 'Unsupported event type: ' . $event['type'] );
			break;
	}
};
