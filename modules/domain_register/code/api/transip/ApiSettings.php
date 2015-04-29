<?php

/**
 * This class holds the settings for the TransIP API.
 *
 * @package Transip
 * @class ApiSettings
 * @author TransIP (support@transip.nl)
 */
class Transip_ApiSettings
{
	/**
	 * The mode in which the API operates, can be either:
	 *		readonly
	 *		readwrite
	 *
	 * In readonly mode, no modifying functions can be called.
	 * To make persistent changes, readwrite mode should be enabled.
	 */
	//public static $mode = 'readwrite';
	public static $mode = 'readonly';

	 /**
	 * TransIP API endpoint to connect to.
	 *
	 * e.g.:
	 *
	 * 		'api.transip.nl'
	 * 		'api.transip.be'
	 * 		'api.transip.eu'
	 */
	public static $endpoint = 'api.transip.nl';

	/**
	 * Your login name on the TransIP website.
	 *
	 */
	public static $login = 'deyron';

	/**
	 * One of your private keys; these can be requested via your Controlpanel
	 */
	public static $privateKey = '
-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQClAphHUMlgQGu8
3dmF2pxEl30Np1XArgyvHlpeNYsbfNLFT8trxpayb7fW+Hv4miGOU/IwsnP04pXp
Sta0COKtftvOqkkPxc7FWDDJfEWwWN+M0MCdiTyQLN27rjxp9CdxHTIG0gSL+6Zu
q3pBD1GmFa6wB5s3qI9INhpt/tJV+i/EKSvzQTQ3sg8MTIAa1xmLdKREwkAFjjLg
1okSPPWbUwIfoP9smU6/bXJDAn8EfrX/YDKe7ymRXpiDsjCBjVweeIQYHNKfhzT8
QNvq7ls+BArh39X3F5B7yJSZXMO3ep3RYcfKqqdX28pvzEhChc77bry8WfyFh6Pt
zLrFs81fAgMBAAECggEAWks/NNcaOtdFnUXwv/ROSqJyxcPpgtQ7EKCVZrP5+QKm
+12cvM8gq2fCu5mhjPoanp2FC+y60ygiTFSthDrQf4vJkB9Sd5UQfqCxoa+lGsin
G5EntYzhXSuP8iF34aq+4oDNXToTTt5XxnuDHJLbZPy8aIrg/uX38dXZRJOKWYz7
IY0KSjNy9m6aMPIG7wwXTImVFbW5NHVYOWqiHGZ7atrpxoHlz4uHHQ2+4TQ8/o+W
tPLZCJFtsc36Q8p6WknzM6hmjnw2TGGob369x4ab5cyX5LkcfgyKAG7+qUJ1QxzV
magGc2RlA9VNt8biDWY3HxuBKrMPKYynt+fuKZhZgQKBgQDOz+nWgxto9Vki+UvC
SNmlhN6SuWK4oQ1hUbAMLkGG+77yVFVZ8jEevOJuqATVkCvPoKYpQU9riRIOVQ/B
o54qq3OdJ9I+rI5wk1kliLUqRR8OHh8mAErqJGwaW7htfEEBs/abwdKig711vlZj
mfVw6hoOq3v2gmX/OwITsCKc4QKBgQDMQX+uXD9XZw52oqChQjpmKIfO/YagfXvj
XxLf7CFLv97XASot0A8jMwkjG55DDezlc9TKrgdKiZCY9eDZ0wnzqVTdo1VECWpZ
S75B4cGyhLSE8W6Hc7rJ+DZoR1BQMfy2qqJ3Q5K626swwBq4yIraUyhG0+rYXU5S
N2mnkrNyPwKBgQDOr5l6SfMl1TXtLwqYs6fYtM6gAmh4LnSp0zLHKZ9RYN03YDMg
vlx65LjDcGXMbdZrHZmSV3Qg+48xBa/GjVewO0NDR53mBxwfxBLu4Q51nTjpKg9l
MhusnxPuz8Wkne513j5S8cUpp9G8D6pUxnQ2EbBTAuzTswRurLFprbkZ4QKBgQCw
aWW4l03R1F2Gk2E+xe+gaiMZ4JgVJywILgYkRlJflUEG26etV8SrNxoOK31eFrnj
V06TGwmLFBNCOSLRn1c6DqcQolAzahpjM2sIhc6Au+MZ61f2Pzs1015SZD12diX5
MpkNftxM7QsHGPQ7YmGivS3NNpzf8H6Dvf5P0AGSiwKBgQCMwuCpTqXwm5ln88XM
Lv3gh94t/Tl1uxTBSG6KiP9MifNwT1DZL6djHj3HUcbksW9LqUVsgdJ1W+HyqlIK
tU/cWiOCYI115POUsL+mvXf3WQlBGY+BrbWEV3HcawXEYpsv6vEqdOdxXvyd2wkC
CppZl5Q9Xlx9fKBvrn1445AZ0g==
-----END PRIVATE KEY-----
';
}
