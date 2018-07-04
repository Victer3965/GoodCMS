<pre>

<?php

class A{
    private $a;
    private $b='';

    function zzz(){
        $a = 'b';
        $this->a = 'OK';
    }
    function get(){
        return $this->b;
    }
}

$a = new A();
$a->zzz();
print_r($a);
echo '<br/>';
print_r($a->get()===null?1:0);
echo '<br/>';

?>

</pre>
<?php

require_once 'classApplicationConfiguration.php';

print_r(ApplicationConfiguration::getProperty('connections', 'default'));

