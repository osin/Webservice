<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31/07/14
 * Time: 14:57
 */

namespace Library;
use Library\Exception\MissingParametersException;
use Library\Handler\HeaderHandler;
use Library\Helper\QueryParser;

/**
 * Class WebServiceGarbage
 * @package Library
 */
class WebServiceGarbage
{

    /**
     * @array arguments
     */
    private $arguments;

    /**
     * @array scheme
     */
    private $scheme;

    /** Data submit during post
     * @var array
     */
    private $data = array();

    /** Get Area
     * @var string
     */
    private $area;

    /** Get the entity type
     * @var string
     */
    private $entityType;

    /** Get the entity value
     * @var string
     */
    private $entityId;

    /** The first child type  in url
     * @var string
     */
    private $firstChildEntityType;

    /** The first child id value in url
     * @var string
     */
    private $firstChildEntityId;

    /** The second child type  in url
     * @var string
     */
    private $secondChildEntityType;

    /** The first child id value in url
     * @var string
     */
    private $secondChildEntityId;

    /** The third child type  in url
     * @var string
     */
    private $thirdChildEntityType;

    /** The third child id value in url
     * @var string
     */
    private $thirdChildEntityId;

    /** Headers from uri is here
     * @var array headers
     */
    private $headers;

    /**
     * @param string $components an url string
     * @param null $data You can provide your own data in __OUTPUT_TYPE__ format
     * @throws MissingParametersException
     */
    public function __construct($components, $data = null)
    {
        if (!is_string($components) || strlen($components) == 0)
            throw new MissingParametersException(__CLASS__, 'Components must to be a string in URI');
        $schema = parse_url($components);
        $path = $schema['path'];
        $this->setScheme($path);
        $this->setArguments();
        $this->setHttpData($data);
        $this->setHeaders();
    }

    /**
     * Set data from httpData
     * @param null $data
     */
    private function setHttpData($data=null){
        if(!$data)
            $data = self::getPhpInput();
        if (!empty($data))
                $this->data = call_user_func_array('Library\Handler\\' . ucfirst(__OUTPUT_TYPE__) . 'Handler::decode',
                    array($data));
    }

    /**
     * Get php://input data
     * @return string
     */
    static function getPhpInput(){
        $httpdata = fopen("php://input", "r");
        $data = fread($httpdata, 1024);
        return $data;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /** return shemeParts
     * @return array|mixed shemeParts
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    function setHeaders()
    {
        $this->headers = HeaderHandler::getHeaderFromClient();
    }

    /** Get Arguments provided by the url
     * @param $components
     *
     * @return array
     */
    private function setScheme($components)
    {
        $schemeParts = explode('/', $components);

        //if url is ending by "/" we remove it
        $last_schemePart = end($schemeParts);
        if (empty($last_schemePart)) {
            array_pop($schemeParts);
        }
        //for example the url /api/secure/accounts/0123456789/case/9876543210/calls/123/notes/11
        switch (count($schemeParts)) {
            case 9:
                $this->thirdChildEntityId = $schemeParts[8]; //11 as note id
            case 8:
                $this->thirdChildEntityType = $schemeParts[7]; //notes
            case 7:
                $this->secondChildEntityId = $schemeParts[6]; //123 as call id
            case 6:
                $this->secondChildEntityType = $schemeParts[5]; //calls
            case 5:
                $this->firstChildEntityId = $schemeParts[4]; //9876543210 as case id
            case 4:
                $this->firstChildEntityType = $schemeParts[3]; //cases
            case 3:
                $this->entityId = $schemeParts[2]; //0123456789 as account id
            case 2:
                $this->entityType = $schemeParts[1]; //accounts
            case 1:
                $this->area = $schemeParts[0]; //secure
            default:
                $this->scheme = $schemeParts; //we retrieve all arguments here
        }
    }

    /** return arguments from URI?args1&args2
     * @return array arguments
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * set argument from URI?args1&args2
     */
    private function setArguments()
    {
        $arguments = $_GET;
        $argumentsProcessed = array();
        array_shift($arguments);
        foreach ($arguments as $key => $argument) {
            $argumentsProcessed[$key] = QueryParser::processListArguments($argument);
        }
        $this->arguments = $argumentsProcessed;
    }

    /** return postArguments
     * @return array data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * @return string
     */
    public function getFirstChildEntityId()
    {
        return $this->firstChildEntityId;
    }

    /**
     * @return string
     */
    public function getFirstChildEntityType()
    {
        return $this->firstChildEntityType;
    }

    /**
     * @return string
     */
    public function getSecondChildEntityId()
    {
        return $this->secondChildEntityId;
    }

    /**
     * @return string
     */
    public function getSecondChildEntityType()
    {
        return $this->secondChildEntityType;
    }

    /**
     * @return string
     */
    public function getThirdChildEntityId()
    {
        return $this->thirdChildEntityId;
    }

    /**
     * @return string
     */
    public function getThirdChildEntityType()
    {
        return $this->thirdChildEntityType;
    }
}