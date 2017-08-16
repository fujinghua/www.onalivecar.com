<?php
/*
 * PHP QR Code encoder
 *
 * Config file, feel free to modify
 */

defined('QR_CACHEABLE') or define('QR_CACHEABLE', true);                                                               // use cache - more disk reads but less CPU power, masks and format templates are stored there
defined('QR_CACHE_DIR') or define('QR_CACHE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR);  // used when QR_CACHEABLE === true
defined('QR_LOG_DIR') or define('QR_LOG_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);                                // default error logs dir

defined('QR_FIND_BEST_MASK') or define('QR_FIND_BEST_MASK', true);                                                          // if true, estimates best mask (spec. default, but extremally slow; set to false to significant performance boost but (propably) worst quality code
defined('QR_FIND_FROM_RANDOM') or define('QR_FIND_FROM_RANDOM', false);                                                       // if false, checks all masks available, otherwise value tells count of masks need to be checked, mask id are got randomly
defined('QR_DEFAULT_MASK') or define('QR_DEFAULT_MASK', 2);                                                               // when QR_FIND_BEST_MASK === false

defined('QR_PNG_MAXIMUM_SIZE') or define('QR_PNG_MAXIMUM_SIZE',  1440);                                                       // maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images
                                                  