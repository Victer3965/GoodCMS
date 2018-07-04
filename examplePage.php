<!DIRECTIVE Page="LayoutPage">
<!DIRECTIVE Master="MasterPage.php">
<php:Content cmp:Placeholder="PageTitle"></php:Content>
<php:Content cmp:Placeholder="PageHead">
    <link type="text/css" rel="stylesheet" href="/styles/main.css"/>
    <style type="text/css">
        .example{
            border:1px solid #333;
        }
        .example>thead>tr>th,
        .example>tbody>tr>td{
            border:1px solid #333;
            padding:5px 10px;
        }
    </style>
</php:Content>
<php:Content cmp:Placeholder="MainArea">
    <php:Image html:src="/images/logo.png" html:width="100" html:height="50"/>
    <php:DataGrid html:class="example" html:border="0" html:cellspacing="0" html:cellpadding="0" cmp:hidden="true" cmp:dataTable="tableUsers"
                  cmp:onRender="dataGrid_onRender">
        <php:Template cmp:role="header">
            <tr>
            <th>ID</th>
            <th>Login</th>
            <th>Email</th>
            </tr>
        </php:Template>
        <php:Template cmp:role="body">
            <tr>
            <td>
                <php:DataField cmp:name="id"></php:DataField>
            </td>
            <td>
                <php:DataField cmp:name="login"></php:DataField>
            </td>
            <td>
                <php:DataField cmp:name="email"></php:DataField>
            </td>
            </tr>
        </php:Template>
    </php:DataGrid>
    <php:DataTable cmp:id="tableUsers" cmp:connection="debug" cmp:onInit="tableUsers_onInit" />
    <php:Panel cmp:id="CoolPanel" cmp:hidden="true">
        This is a Panel 1
        <php:Button cmp:onClick="button_Click">
            Push the button!
        </php:Button>
        <php:Panel cmp:hidden="true">
            This is a Panel 2
        </php:Panel>
        <php:Panel cmp:hidden="true">
            <?php //echo "<!--\n".print_r($_SERVER, true)."\n-->"; ?>
        </php:Panel>
    </php:Panel>
    <php:WidgetHTML><settings><html>Hello dear friends!</html></settings></php:WidgetHTML>
</php:Content>

<?php

function tableUsers_onInit($object, $event = null)
{
    echo '<!--tableUsers_onInit-->';
    $object->query="SELECT u.id id, l.value login, e.value email FROM `gc_users` u 
    LEFT JOIN gc_users_props l ON l.user_id=u.id AND l.property='login'
        LEFT JOIN gc_users_props e ON e.user_id=u.id AND e.property='email'";
}

function button_Click($object, $event = null)
{

}

?>
<?php

function dataGrid_onRender($object, $event = null)
{
    echo 'Hello, onRender!';
}

