<?php
/**
 * ezinfo
 * @copyright Copyright (C) 2010 - Philippe Vincent-Royol. All rights reserved
 * @licence http://www.gnu.org/licenses/gpl-2.0.txt GNU GPLv2
 * @author Philippe Vincent-Royol
 * @version @@@VERSION@@@
 * @package pvrupdatesocial
 */
class pvrupdatesocialInfo
{
    static function info()
    {
        return array( 'Name'      => '<a href="http://projects.ez.no/pvrupdatesocial" target="_blank"> pvrUpdateSocial </a>',
                      'Version'   => '@@@VERSION@@@',
                      'Copyright' => 'Copyright 2010 - '.date('Y').' Philippe Vincent-Royol',
                      'Author'   => '<a href="http://www.pheelit.fr" target="_blank">Philippe Vincent-Royol</a>'
                    );
    }
}
