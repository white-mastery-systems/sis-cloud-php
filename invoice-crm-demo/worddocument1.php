<?php
require_once 'pathToPHPDocX/classes/CreateDocx.inc';

$docx = new CreateDocx();

$html='<style>
ul {color: blue; font-size: 14pt; font-family: Cambria}
table {border: 1px solid green}
td {font-family: Arial}
#redBG {background-color: red; color: #f0f0f0}
.firstP {margin-left: 220px}
</style>

<p class="firstP">This is a simple paragraph with <strong>text in bold</strong>.</p>
<p>Now we include a list:</p>
<ul>
    <li>First item.</li>
    <li>Second item with subitems:
        <ul>
            <li style="color: red">First subitem.</li>
            <li>Second subitem.</li>
        </ul>
    </li>
    <li id="redBG">Third subitem.</li>
</ul>
<p>And now a table:</p>
<table>
    <tbody><tr>
        <td style="background-color: #ffff00">Cell 1 1</td>
        <td>Cell 1 2</td>
    </tr>
    <tr>
        <td>Cell 2 1</td>
        <td>Cell 2 2</td>
    </tr>
</tbody></table>';

$docx->embedHTML($html);

$docx->createDocx('simpleHTML');
?>