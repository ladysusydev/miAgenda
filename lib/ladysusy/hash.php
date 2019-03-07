<?php
/**
 * LSCorePHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2019, LadySusy
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package		LSCorePHP
 * @author		LadySusy Dev
 * @copyright	Copyright (c) 2019, LadySusy (http://www.ladysusy.org/)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

// Avoid direct access
defined('_LS') or die;

/**
 * class de hash
 */
class LSHash
{
    /**
     * @var string, algorit Blowfish
     */
    private static $_salt = '$2a$';

    /**
     * @var int cost value
     */
    private static $_cost = '10';

    /**
     * Generating character string
     *
     * @return string Text string to use with the hash
     */
    public static function uniqueSalt()
    {
        return substr(sha1(mt_rand()), 0, 22);
    }

    /**
     * Hash generator
     *
     * @param string Spassword The password provided
     *
     * @return string The hash
     */
    public static function hash($password)
    {
        return crypt($password, self::$_salt . self::$_cost . '$' . self::uniqueSalt());
    }

    /**
     * Password comparator with hash
     *
     * @param string $hash
     * @param string $password Password provided by the user
     *
     * @return string The hash value
     */
    public static function compPassword($hash, $password)
    {
        $fullSalt = substr($hash, 0, 29);
        $newHash = crypt($password, $fullSalt);

        return ($hash == $newHash);
    }
}
