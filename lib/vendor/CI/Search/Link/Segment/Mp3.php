<?php
class CI_Search_Link_Segment_Mp3 extends CI_Search_Link_Segment
{ 
  protected function buildLuceneCriteria(sfParameterHolder $parameters)
  {
    $c = parent::buildLuceneCriteria($parameters);
    $c->addField('mime_type', 'audio/mpeg');
    
    return $c;
  }
}