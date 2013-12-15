<?php

// +----------------------------------------------------------------------------------------+
// | File: sepaclass.php                                                                    |
// | Author: Marcel Groot, marcelgroot@svsparta.nl                                          |
// |                                                                                        |
// | Revision 2 Feb 2013                                                                    |
// | - Placed <Ctry> tag direct after <PstlAdr> after feedback from Rabobank                |
// |   at Creditor                                                                          |
// |                                                                                        |
// | Revision 18 Jan 2013                                                                   |
// | -Implemented feedback from Raboank and obtained their formatbook version 1.0           |
// |  dated 14Feb2011 from https://www.rabobank.com/en/float/fl/downloads.html              |
// |  - Deleted <Nm> tag in <CdtrScheId>                                                    |
// |  - Changed <CtryOfRes> back into <Ctry> at <Creditor>                                  |
// |  - Changed <Document....> tag according Rabobank format,                               |
// |    The example in the XML message, guideline showsat least a typing error              |
// | Note1: there are (again ) inconsistencies between this Rabobank format book and        |
// | the XML message for SEPA Direct Debit Initiation Implementation Guidelines for the     |
// | Netherlands.  Mr Frans Rietbergen would discuss internally at the Rabobank             |
// | Note2: according the Rabobank formaten book only true is allowed at batchbooking       |
// | during the first phase of SEPA                                                         |
// |                                                                                        |
// | Revision: 3 Jan 2013                                                                   |
// | - Updated according comments Rabobank                                                  |
// | - updated reference for                                                                |
// |    XML message for SEPA Direct Debit Inititiation                                      |
// |    Implementation Guidelines for the Netherlands                                       |
// |    Vereniging van Nederlandse Banken                                                   |
// |   from Version 2.2 - Februari 2011 to Version 6.0 - March 2012                         |
// | - Took out <GRP> tag                                                                   |
// | - Changed <Ctry> into <CtryOfRes> at <Creditor>                                        |
// +----------------------------------------------------------------------------------------+

// +----------------------------------------------------------------------------------------+
// | As per February 2014, payments are only possible with the IBAN                         |
// | (International Bank Account Number).                                                   |
// | Everybody has to use the new standards for payments and incasso's as per               |
// | 1 February 2014, which date has been determined by European law.                       |
// | As per this date all 32 countries within the SEPA (Single European Payment Area)       |
// | have to be switched over to the IBAN.                                                  |
// | De national clientbank- and bankclient standards will be replaced by the               |
// | SEPA XML formaat.  This SEPA format is about a format used by companies to standardize |
// | payments. For example the ClieOp file format or the BTL91 format will no longer be     |
// | in use. A new XML file format according Pain.008.001.02 format will  become mandatory. |
// |                                                                                        |
// | Each such XML message consists out of three main elements:                             |
// | - A Group header block, which contains general information.                            |
// | - A Payment Information block, which contains general information as well as one or    |
// |   more Transaction Information blocks.                                                 |
// | - A Transaction Information block, which is contained in a Payment Information block   |
// |   and contains details about the financial transaction.                                |
// |                                                                                        |
// | Dependent on local bank rules, different grouping methods are allowed                  |
// | - SINGLE: Indicates that for each Payment Information Block there shall be only one (1)|
// |   Transaction Information Block.                                                       |
// | - GROUPED: Indicates that there shall be only one (1) Payment Information Block, in    |
// |   which multiple Transaction Information blocks may be present.                        |
// | - MIXED: Indicates that there can be one or more Payment Information block(s) and      |
// |   each such block can contain one or more Transaction Information block(s)             |
// |                                                                                        |
// |   SINGLE                       GROUPED                      MIXED                      |
// |   Group Header                 Group Header                 Group Header               |
// |   Payment Information 1        Payment Information 1        Payment Information 1      |
// |    Transaction Information 1    Transaction Information 1    Transaction Information 1 |
// |   Payment Information 2         Transaction Information 2    Transaction Information 2 |
// |    Transaction Information 2    Transaction Information 3   Payment Information 2      |
// |   Payment Information 3                                      Transaction Information 3 |
// |    Transaction Infromation 3                                 Transaction Information 4 |
// |                                                                                        |
// |                                                                                        |                                                    
// | In this script three classes are defined to generate an XML file according             |
// | the pain.008.001.02 format:                                                            |
// | - The class PaymentInformation, which builts up a PaymentInformation block.            |
// |   Each Payment Information block shall be added into an entity of class XMLPain.       |
// | - The class DirectDebitTransactionInformation, which is a storage area for the         |
// |   information about a single debitor. This Transaction Information block shall be      |
// |   added for each debitor into an entity of a class PaymentInformation.                 |
// +----------------------------------------------------------------------------------------+
  
// +-------------------------------------------------------------------------------------------------------------------+  
// | Variables used inside the XML text,  refer to below publication which can be downloaded from www.sepanl.nl:       |
// |                                                                                                                   |
// | 'XML message for SEPA Direct Debit Inititiation                                                                   |
// |  Implementation Guidelines for the Netherlands                                                                    |
// |  Vereniging van Nederlandse Banken                                                                                |
// |  Version 6.0 - March 2012'                                                                                     |
// |                                                                                                                   |
// |  Example given:                                                                                                   |
// |  1.1 <MsgId> [1..1], MessageIdentification                                                                        |
// |  Format: Text, MaxLength: 35, MinLenght: 1                                                                        |
// |  $_MsgId     = "incassobatch $timestamp";                                                                         |
// |                                                                                                                   |
// | "1.1" is the index where this variable is specified in the above mentioned publication                            |
// | "<MsgId> is the XML tag according above mentioned publication                                                     |
// | "[1..1] shows the occurence of the element according above mentioned publication                                  |
// | Note:  [0..1] shows that the element can be present 0 times or 1 time. The element is optional                    |
// |        [1..1] shows that the element can only be present 1 time. The element is mandatory                         |
// |        [1..n] shows that the element is mandatory and can be present 1 to n times                                 |
// | "MessageIdentification" shows the full wording for the abbreviated XML tag according above mentioned publication  |
// | "Format: Text, MaxLength: 35, MinLenght: 1" shows the format according above mentioned publication                |
// | "$_MsgId" is the variable name used in this script for XML tag <MsgId>                                             |
// |                                                                                                                   |
// +-------------------------------------------------------------------------------------------------------------------+ 

