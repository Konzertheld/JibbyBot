<?php

/**
 * Allows administrators to effectively speak and act as the bot.
 */
class Phergie_Plugin_Puppet extends Phergie_Plugin_Abstract_Command
{
    /**
     * Flag indicating whether or not the plugin is an admin plugin or not
     *
     * @var bool
     */
    public $needsAdmin = true;

    /**
     * Handles a request for the bot to repeat a given message in a specified
     * channel.
     *
     * <code>say #chan message</code>
     *
     * @param string $chan Name of the channel
     * @param string $message Message to repeat
     * @return void
     */
    public function onDoSay($chan, $message)
    {
        $this->doPrivmsg($chan, $message);
    }

    /**
     * Handles a request for the bot to repeat a given action in a specified
     * channel.
     *
     * <code>act #chan action</code>
     *
     * @param string $chan Name of the channel
     * @param string $action Action to perform
     * @return void
     */
    public function onDoAct($chan, $action)
    {
        $this->doAction($chan, $action);
    }

    /**
     * Handles a request for the bot to send the server a raw message
     *
     * <code>raw message</code>
     *
     * @param string $message Message to send
     * @return void
     */
    public function onDoRaw($message)
    {
        $user = $this->event->getNick();
        if ($this->fromAdmin(true)) {
            $this->doRaw($message);
        } else {
            $this->doNotice($user, 'You do not have permission to send raw messages.');
        }
    }
}
