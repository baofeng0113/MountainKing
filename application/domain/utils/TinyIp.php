<?php

class Domain_Util_TinyIp
{
    protected $ipdataHandle = '';

    public function __construct($ipdataHandle)
    {
        if (!$this->ipdataHandle = fopen($ipdataHandle, 'rb'))
            return false;
    }

    public function convert($ip)
    {
        static $fp = null, $offset = array(), $index = null;
        $ipdot = explode('.', $ip);
        $ip = pack('N', ip2long($ip));
        $ipdot[0] = (int)$ipdot[0];
        $ipdot[1] = (int)$ipdot[1];
        if (!$this->ipdataHandle) return false;
        $offset = unpack('Nlen', fread($this->ipdataHandle, 4));
        $index = fread($this->ipdataHandle, $offset['len'] - 4);
        $length = $offset['len'] - 1028;
        $start = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);
        for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {
            if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
                $index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
                $index_length = unpack('Clen', $index{$start + 7});
                break;
            }
        }
        fseek($this->ipdataHandle, $offset['len'] + $index_offset['len'] - 1024);
        if (!$index_length['len']) return 'Unknown';
        return fread($this->ipdataHandle, $index_length['len']);
    }
}
