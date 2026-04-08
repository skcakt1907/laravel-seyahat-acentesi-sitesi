<?php
$dir = '/tmp/tcm_pages';
$out = [];
foreach (glob("$dir/*.html") as $f) {
    $slug = basename($f, '.html');
    $html = file_get_contents($f);
    $img = null;
    if (preg_match('#<meta property="og:image" content="([^"]+)"#', $html, $m)) $img = $m[1];
    // Extract entry-content paragraphs
    $desc = '';
    if (preg_match('#<div[^>]*class="[^"]*entry-content[^"]*"[^>]*>(.*?)</div>#s', $html, $m)) {
        $content = $m[1];
        preg_match_all('#<p[^>]*>(.*?)</p>#s', $content, $pm);
        $paras = [];
        foreach ($pm[1] as $p) {
            $txt = trim(strip_tags($p));
            $txt = html_entity_decode($txt, ENT_QUOTES|ENT_HTML5, 'UTF-8');
            if (strlen($txt) > 40) $paras[] = $txt;
            if (count($paras) >= 3) break;
        }
        $desc = implode(' ', $paras);
    }
    $out[$slug] = ['img' => $img, 'desc' => mb_substr($desc, 0, 600)];
}
file_put_contents('/tmp/tcm_extract.json', json_encode($out, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
echo "done\n";
