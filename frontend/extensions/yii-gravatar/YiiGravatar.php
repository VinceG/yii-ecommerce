<?php
/**
 * YiiGravatar class file.
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 */

Yii::import('system.web.widgets.CWidget');

/**
 * YiiGravatar class.
 *
 * YiiGravatar displays an Gravatar image on a page. The Gravatar is specified via the
 * {@link setEmail email} property.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->widget('ext.yii-gravatar.YiiGravatar', array(
 *     'email'=>'malyshev.php@gmail.com',
 *     'size'=>80,
 *     'defaultImage'=>'http://www.amsn-project.net/images/download-linux.png',
 *     'secure'=>false,
 *     'rating'=>'r',
 *     'emailHashed'=>false,
 *     'htmlOptions'=>array(
 *         'alt'=>'Gravatar image',
 *         'title'=>'Gravatar image',
 *     )
 * ));
 * </pre>
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 * @version $Id$
 * @package
 * @since 1.1.7
 */
class YiiGravatar extends CWidget
{

    const PUBLIC_API_URL = 'http://www.gravatar.com/avatar/';

    const SECURE_API_URL = 'https://secure.gravatar.com/avatar/';

    private $_emailHashed = false;

    /**
     * @var boolean whether to use SSL connection
     */
    private $_secure = false;

    /**
     * @var integer the Gravatar image size
     */
    private $_size = 80;

    /**
     * @var string the Gravatar image email
     */
    private $_email;

    /**
     * @var string the Gravatar default image
     */
    private $_defaultImage;

    /**
     * @var array list of possible Gravatar default image values
     */
    private $_defaultImages = array(
        '404', 'mm', 'identicon',
        'monsterid', 'wavatar', 'retro'
    );

    private $_rating = 'g';

    /**
     * @var array list of possible Gravatar image rating values
     */
    private $_ratings = array(
        'g', 'pg', 'r', 'x'
    );

    /**
     * @internal the Gravatar image url
     */
    private $_imageUrl;

    /**
     * @var array the HTML attributes that should be rendered in the HTML tag representing the Gravatar image
     */
    public $htmlOptions = array();

    /**
     * This method overrides the parent implementation by rendering a normal image, using an IMG tag.
     * To get an image specific to a user need to set {@link setEmail email} property.
     * {@inheritdoc}
     */
    public function run()
    {
        $altText = isset($this->htmlOptions['alt']) ? $this->htmlOptions['alt'] : '';
        $imageTag = CHtml::image($this->getImageUrl(), $altText, $this->htmlOptions);
        echo $imageTag;
    }

    /**
     * @return string the Gravatar image URL
     */
    public function getImageUrl()
    {
        if (null === $this->_imageUrl)
        {
            $this->_imageUrl = $this->_secure ? self::SECURE_API_URL : self::PUBLIC_API_URL;
            $this->_imageUrl .= $this->getEmailHashed() 
                    ? $this->email
                    : md5(strtolower(trim($this->email)));
            $this->_imageUrl .= '?' . http_build_query($this->getApiParams());
        }

        return $this->_imageUrl;
    }

    /**
     * @return array the Gravatar api parameters
     */
    public function getApiParams()
    {
        return array(
            'd' => $this->_defaultImage,
            'r' => $this->_rating,
            's' => $this->_size
        );
    }

    /**
     * Set Gravatar image rating. Posible values 'g', 'pg', 'r' or 'x'.
     * @see http://en.gravatar.com/site/implement/images/#rating
     * @param string $value the rating value
     * @throws CException if the value is not in the possible values list
     */
    public function setRating($value)
    {
        $value = strtolower($value);
        if (false === in_array($value, $this->_ratings))
        {
            throw new CException(Yii::t('application','Invalid rating value "{value}". Please make sure it is among ({enum}).',
				array('{value}'=>$value, '{enum}'=>implode(', ',$this->_ratings))));
        }

        $this->_rating = $value;
    }

    /**
     * @return string the Gravatar image rating
     */
    public function getRating()
    {
        return $this->_rating;
    }

    /**
     * Set Gravatar default image. Will displayed when an email address has no matching Gravatar image.
     * Posible values '404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro'
     * or absolute URL to the image file to use your own image.
     * @see http://en.gravatar.com/site/implement/images/#default-image
     * @param string $value the default Gravatar image
     * @throws CException if the value is not in the possible values list or it is not absolute URL to the image file.
     */
    public function setDefaultImage($value)
    {
        if (false === (strpos($value, '://')) && false === in_array($value, $this->_defaultImages))
        {
            throw new CException(Yii::t('application','Invalid default image value "{value}". Please make sure it is among ({enum}) or it is absolute URL to the image file.',
				array('{value}'=>$value, '{enum}'=>implode(', ',$this->_defaultImages))));
        }
        $this->_defaultImage = $value;
    }

    /**
     * @return string the default Gravatar image
     */
    public function getDefaultImage()
    {
        return $this->_defaultImage;
    }

    /**
     * @see http://en.gravatar.com/emails/
     * @param string $email the Gravatar image email address
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return string the Gravatar image email address
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @see http://en.gravatar.com/site/implement/images/#secure-images
     * @param boolean $value whether displaying Gravatar images being served over SSL
     */
    public function setSecure($value = true)
    {
       $this->_secure = CPropertyValue::ensureBoolean($value);
    }

    /**
     * @return boolean whether displaying Gravatar images being served over SSL
     */
    public function getSecure()
    {
        return $this->_secure;
    }

    /**
     * Set the Gravatar image size. You may request images anywhere from 1px up to 512px,
     * however note that many users have lower resolution images, so requesting larger
     * sizes may result in pixelation/low-quality images.
     * By default, images are represented at 80px by 80px if no size parameter is supplied.
     * @see http://en.gravatar.com/site/implement/images/#size
     * @param integer $value the Gravatar image size
     */
    public function setSize($value)
    {
        $value = CPropertyValue::ensureInteger($value);

        if ($value < 1 || $value > 512)
        {
            throw new CException(Yii::t('application','Invalid Gravatar size value "{value}". Please make sure it is between {min} and {max} (inclusive).',
				array('{value}'=>$value, '{min}'=>1, '{max}'=>512)));
        }

        $this->_size = $value;
    }

    /**
     * @return integer the Gravatar image size
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Set whether the Gravatar image email already hashed
     * @see http://en.gravatar.com/site/implement/hash/
     * @param boolean $value whether the Gravatar image email already hashed
     */
    public function setEmailHashed($value = true)
    {
        $this->_emailHashed = CPropertyValue::ensureBoolean($value);
    }

    /**
     * @return boolean whether the Gravatar image email already hashed
     */
    public function getEmailHashed()
    {
        return $this->_emailHashed;
    }

}