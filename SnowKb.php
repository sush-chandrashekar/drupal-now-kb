<?php
/* PHP code to pull KB article from specific knowledge base */
namespace Drupal\snow_integration\Plugin\Block;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Block\BlockBase;

/**
 * Block of Cat Facts... you can't make this stuff up.
 *
 * @Block(
 *   id = "snow_integration",
 *   admin_label = @Translation("Snow test")
 * )
 */
class SnowTestClient extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var \GuzzleHttp\Client $client */
    $client = \Drupal::service('http_client_factory')->fromOptions([

      'headers' => [
     'Authorization' => 'Basic **authcode***',
     'Content-Type' => 'application/hal+json'
  ]
    ]);

  /* We are pulling from 2 knowledge bases, getting the latest 10 articles */

    $response = $client->get('https://instance.service-now.com/api/now/table/kb_knowledge?sysparm_query=sys_class_name!=kb_knowledge_block^workflow_state=published^kb_knowledge_base=a7e8a78bff0221009b20ffffffffff17^ORkb_knowledge_base=bb0370019f22120047a2d126c42e7073^ORDERBYDESCsys_updated_on&sysparm_limit=10');

    $restResponse = Json::decode($response->getBody());
    $kbs = [];
    $item = '';
    foreach ($restResponse as $eachResp) {
      foreach($eachResp as $eachkb){

        $linkkb = "https://instance.service-now.com/escdemo?id=kb_article&sys_id=".$eachkb['sys_id'];
        $item .= '<a  target="_blank" href="'.$linkkb.'">'.$eachkb['short_description'].'</a><br/><br/>';
      }

    }

    return [

      '#type' => 'markup',
      '#markup' => $item,
    ];
  }

}