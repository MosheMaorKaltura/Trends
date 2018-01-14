#!/bin/bash

export Interval=$1
export partner_id=$2

command='{"index":"kaltura_entry","ignore_unavailable":true,"preference":1514882480224}\n{"size":1,"aggs":{"2":{"date_histogram":{"field":"created_at","interval":"'$Interval'd","time_zone":"Asia/Amman","min_doc_count":1}}},"version":true,"query":{"bool":{"must":[{"query_string":{"query":"*","analyze_wildcard":true}},{"query_string":{"query":"partner_id:'$partner_id'","analyze_wildcard":true}},{"range":{"created_at":{"gte":1451731541201,"lte":1514757599999,"format":"epoch_millis"}}}],"must_not":[]}},"_source":["partner_id"],"highlight":{"pre_tags":["@kibana-highlighted-field@"],"post_tags":["@/kibana-highlighted-field@"],"fields":{"*":{"highlight_query":{"bool":{"must":[{"query_string":{"query":"*","analyze_wildcard":true,"all_fields":true}},{"query_string":{"query":"partner_id:'$partner_id'","analyze_wildcard":true,"all_fields":true}},{"range":{"created_at":{"gte":1451731541201,"lte":1514757599999,"format":"epoch_millis"}}}],"must_not":[]}}}},"fragment_size":2147483647}}\n'
url="'http://192.168.21.166:5601/elasticsearch/_msearch'"
content_type="'content-type: application/x-ndjson'"
response_type="'Accept: application/json, text/plain, */*'"
kbn_version="'kbn-version: 5.4.1'"

total_command="curl $url -H $kbn_version -H $content_type -H $response_type --data-binary \$'$command' --compressed"
eval $total_command 

