<?php

require_once 'classWidget.php';

class WidgetDataView extends Widget
{
    /**
     * @var \DataProviders\DataProvider $provider
     */
    private $provider;
    private $data;

    public function edit()
    {
        // TODO: Implement edit() method.
    }

    protected function renderInternal(&$stream)
    {
        $this->initProvider();
        $this->getData();

        $stream[] = '<div calss="dataview">\n<table calss="dataview">\n<thead>';
        foreach ($this->settings->cols as $col) {
            $stream[] = '<th>' . $col . '</th>\n';
        }
        $stream[] = '</thead>\n<tbody>\n';

        if (!isset($this->data)) {
            $stream[] = '<tr><td colspan="' . count($this->settings->cols) . '">Data not received.</td>';
            return;
        }

        foreach ($this->data as $tr) {
            $stream[] = '<tr>\n';
            for ($i = 0; $i < count($tr); $i++) {
                $td = $tr[$i];
                $stream[] = '<td>' . $td . '</td>\n';
            }
            $stream[] = '</tr>\n';
        }
        $stream[] = '</tbody>\n</div>\n';
    }

    private function initProvider()
    {
        if ((string)$this->settings->provider) {
            $connectionProperties = [
                'provider' => $this->settings->provider,
                'connectionString' => $this->settings->connectionString,
                'user' => $this->settings->user,
                'password' => $this->settings->password
            ];
        } else if ((string)$this->settings->connection) {
            $connectionProperties = ApplicationConfiguration::getProperty('connections', $this->connection);
        }
        $providerClass = $this->settings->provider;
        $this->provider = new $providerClass($connectionProperties);
    }

    public function getData()
    {
        $this->provider->connect();
        $this->data = $this->provider->query($this->settings->query);
    }
}
