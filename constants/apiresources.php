<?php namespace ObitechBilmapay\LaravelPaystackSdk\Constants;

/* Transaction api resources */
const INIT_TRANS = "/transaction/initialize";
const VERIFY_TRANS = "/transaction/verify/:pathParam";
const LIST_TRANS = "/transaction";
const FETCH_TRANS = "/transaction/:pathParam";
const CHARGE_TRANS_AUTH = "/transaction/charge_authorization";
const CHECK_TRANS_AUTH = "/transaction/check_authorization";
const VIEW_TRANS_TIMELINE = "/transaction/timeline/:pathParam";
const TRANS_TOTAL = "/transaction/totals";
const TRANS_EXPORT = "/transaction/export";
const TRANS_PARTIAL_DEBIT = "/transaction/partial_debit";