<?php

namespace Ronanchilvers\Foundation\Queue\Exception;

use Exception;

/**
 * Exception thrown for failed jobs which will not be re-run
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class FatalException extends Exception
{}
