<?php

namespace App\Modules\SubscriberManagement\Commands;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandHeader;
use App\Modules\SubscriberManagement\Helpers\ConfigureCommandBody;

class CommandMaker
{
    private $entitleCommand;
    private $messageCommand;
    private $blackListCommand;
    private $fingerPrintCardCommand;
    private $emailCommand;
    public function __construct(
        EntitleCommand $entitleCommand,
        MessageCommand $messageCommand,
        BlackListCommand $blackListCommand,
        FingerPrintCommand $fingerPrintCardCommand,
        EmailCommand $emailCommand
    )
    {
        $this->entitleCommand = $entitleCommand;
        $this->messageCommand = $messageCommand;
        $this->blackListCommand = $blackListCommand;
        $this->fingerPrintCardCommand = $fingerPrintCardCommand;
        $this->emailCommand = $emailCommand;
    }

    public function getEntitleCommand($data){
        $configure_command_header = new ConfigureCommandHeader();
        $configure_command_body = new ConfigureCommandBody();

        return $this->entitleCommand->getCommand($data, $configure_command_header, $configure_command_body);
    }

    public function getMessageCommand($data){
        $configure_command_header = new ConfigureCommandHeader();
        $configure_command_body = new ConfigureCommandBody();
        return $this->messageCommand->getCommand($data, $configure_command_header, $configure_command_body);
    }

    public function getBlackListCommand($data){
        $configure_command_header = new ConfigureCommandHeader();
        $configure_command_body = new ConfigureCommandBody();
        return $this->blackListCommand->getCommand($data, $configure_command_header, $configure_command_body);
    }

    public function getFingerPrintCommand($data){
        $configure_command_header = new ConfigureCommandHeader();
        $configure_command_body = new ConfigureCommandBody();
        return $this->fingerPrintCardCommand->getCommand($data, $configure_command_header, $configure_command_body);
    }

    public function getEmailCommand($data){
        $configure_command_header = new ConfigureCommandHeader();
        $configure_command_body = new ConfigureCommandBody();
        return $this->emailCommand->getCommand($data, $configure_command_header, $configure_command_body);
    }
}