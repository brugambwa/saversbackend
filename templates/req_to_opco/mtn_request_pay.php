<?php
$mtn_request_pay = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="http://b2b.mobilemoney.mtn.zm_v1.0">
   <soapenv:Header>
      <RequestSOAPHeader xmlns="http://www.huawei.com.cn/schema/common/v2_1">
         <spId>' . $data['spID'] . '</spId>
         <spPassword>' . $data['spPassword'] . '</spPassword>
         <serviceId/>
         <timeStamp>' . $data['timeStamp'] . '</timeStamp>
      </RequestSOAPHeader>
   </soapenv:Header>
   <soapenv:Body>
      <b2b:processRequest>
         <name>serviceId</name>
         <value>101</value>
         <parameter>
            <name>DueAmount</name>
            <value>' . $data['DueAmount'] . '</value>
         </parameter>
         <parameter>
            <name>MSISDNNum</name>
            <value>' . $data['MSISDNNum'] . '</value>
         </parameter>
         <parameter>
            <name>ProcessingNumber</name>
            <value>' . $data['ProcessingNumber'] . '</value>
         </parameter>
         <parameter>
            <name>serviceId</name>
            <value>crespay.sp</value>
         </parameter>
         <parameter>
            <name>AcctRef</name>
            <value>' . $data['AcctRef'] . '</value>
         </parameter>
         <parameter>
            <name>AcctBalance</name>
            <value>' . $data['AcctBalance'] . '</value>
         </parameter>
         <parameter>
            <name>MinDueAmount</name>
            <value>' . $data['MinDueAmount'] . '</value>
         </parameter>
         <parameter>
            <name>Narration</name>
            <value>' . $data['Narration'] . '</value>
         </parameter>
         <parameter>
            <name>PrefLang</name>
            <value>121212121</value>
         </parameter>
         <parameter>
            <name>OpCoID</name>
            <value>' . $data['OpCoID'] . '</value>
         </parameter>
      </b2b:processRequest>
   </soapenv:Body>
</soapenv:Envelope>';

