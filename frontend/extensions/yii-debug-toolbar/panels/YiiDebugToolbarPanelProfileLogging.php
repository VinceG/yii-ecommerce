<?php
/**
 * YiiDebugToolbarPanelProfileLogging class file.
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 */


/**
 * YiiDebugToolbarPanelProfileLogging represents an ...
 *
 * Description of YiiDebugToolbarPanelProfileLogging
 *
 * @author Sergey Malyshev <malyshev.php@gmail.com>
 * @author Igor Golovanov <igor.golovanov@gmail.com>
 * @version $Id$
 * @package YiiDebugToolbar
 * @since 1.1.7
 */
class YiiDebugToolbarPanelProfileLogging extends YiiDebugToolbarPanel
{
    /**
     * Message count.
     *
     * @var integer
     */
    private $_countMessages;

    /**
     * Logs.
     *
     * @var array
     */
    private $_logs;

    /**
     * {@inheritdoc}
     */
    public function getMenuTitle()
    {
        return YiiDebug::t('Profile Logging');
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuSubTitle()
    {
        return YiiDebug::t('{n} message|{n} messages', array($this->countMessages));
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return YiiDebug::t('Profile Log Messages');
    }

    /**
     * Get logs.
     *
     * @return array
     */
    public function getLogs()
    {
        if (null === $this->_logs)
        {
            $this->_logs = $this->filterLogs();
        }
        return $this->_logs;
    }

    /**
     * Get count of messages.
     *
     * @return integer
     */
    public function getCountMessages()
    {
        if (null === $this->_countMessages)
        {
            $this->_countMessages = count($this->logs);
        }
        return $this->_countMessages;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->render('profile_logging', array(
            'logs' => $this->logs
        ));
    }

    /**
     * Get filter logs.
     *
     * @return array
     */
    protected function filterLogs()
    {
        $logs = array();
        foreach ($this->owner->getLogs() as $entry)
        {            
            if (CLogger::LEVEL_PROFILE === $entry[1] &&  false !== strpos($entry[2], 'profile.'))
            {
                $logs[] = $entry;
            }
        }
        return $logs;
    }
}
