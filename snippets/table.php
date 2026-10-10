<?php
use Kirby\Cms\App;

$csl_json_keys = ["author", "title", "type", "accessed.date-parts.0"];
$table_keys = ["auteur⋅ices", "titre", "type", "date"];
$array = json_decode(json_encode($data), true);

function get_author($array)
{
    $parts = [];
    if (array_key_exists("author", $array)) {
        $authors = $array["author"];
        foreach ($authors as $author) {
            if (key($author) == "literal") {
                array_push($parts, $author["literal"]);
            } else {
                $name = mb_strtoupper($author["family"], "UTF-8") . " " . $author["given"];
                array_push($parts, $name);
            }
        }
    }
    $str = implode(", ", $parts);
    return $str;
}

function get_source($array)
{
    if (array_key_exists("container-title", $array)) {
        $source = $array["container-title"];
        if (array_key_exists("issue", $array)) {
            $source . " " . $array["issue"];
        }
        return $source;
        // }elseif (array_key_exists("source", $array)){
        //   return $array["source"];
    } elseif (array_key_exists("event-title", $array)) {
        return $array["event-title"];
    } else {
        return false;
    }
}

function get_date_iso($array)
{
    if (array_key_exists("issued", $array)) {
        $date = $array["issued"]["date-parts"][0];
        return ($date[0] . (isset($date[1]) ? str_pad($date[1], 2, "0", STR_PAD_LEFT) : "00") . (isset($date[2]) ? str_pad($date[2], 2, "0", STR_PAD_LEFT) : "00"));
    }
}

function get_date($array)
{
    if (array_key_exists("issued", $array)) {
        $date = $array["issued"]["date-parts"][0];
        return (isset($date[2]) ? str_pad($date[2], 2, "0", STR_PAD_LEFT) . "/" : "") . (isset($date[1]) ? str_pad($date[1], 2, "0", STR_PAD_LEFT) . "/" : "") . $date[0];
    }
}

function get_title($array)
{
    if(array_key_exists("title",$array)){
        return $array["title"];
    }
}

?>

<!-- <?php css(['/media/plugins/corentinbrule/citeproc/table.css']); ?> -->

<link rel="stylesheet" href="<?= App::plugin('corentinbrule/citeproc')->asset('table.css') ?>">

<input type="text" class="table-search" placeholder="Rechercher dans la bibliographie…"></table>
<table class="biblio-table">
    <thead>
        <tr>
            <!-- <th class="sort-id"></th> -->
            <th class="sort-author">authors</th>
            <th class="sort-title">title</th>
            <th class="sort-type">type</th>
            <th class="sort-date">date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($array as $item): ?>
            <?php $id = (isset($item["id"]) ? $item["id"] : '' ) ?>
            <tr data-shortcode="(cite: <?= $id ?>)">
                <!-- <td class="bib-id">
                    <button data-key="<?= $id ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" data-type="key" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.7577 11.8279L18.6066 3.979L20.0208 5.39322L18.6066 6.80743L21.0815 9.28231L19.6673 10.6965L17.1924 8.22164L15.7782 9.63586L17.8995 11.7572L16.4853 13.1714L14.364 11.0501L12.1719 13.2421C13.4581 15.1835 13.246 17.8249 11.5355 19.5354C9.58291 21.488 6.41709 21.488 4.46447 19.5354C2.51184 17.5827 2.51184 14.4169 4.46447 12.4643C6.17493 10.7538 8.81633 10.5417 10.7577 11.8279ZM10.1213 18.1211C11.2929 16.9496 11.2929 15.0501 10.1213 13.8785C8.94975 12.7069 7.05025 12.7069 5.87868 13.8785C4.70711 15.0501 4.70711 16.9496 5.87868 18.1211C7.05025 19.2927 8.94975 19.2927 10.1213 18.1211Z"></path></svg>
                    </button>
                    <button data-cite="<?= $id ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" data-type="quote" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.58341 17.3211C3.55316 16.2275 3 15 3 13.0104C3 9.51092 5.45651 6.37372 9.03059 4.82324L9.92328 6.20085C6.58804 8.00545 5.93618 10.3461 5.67564 11.8221C6.21263 11.5444 6.91558 11.4467 7.60471 11.5106C9.40908 11.6778 10.8312 13.1591 10.8312 15C10.8312 16.933 9.26416 18.5 7.33116 18.5C6.2581 18.5 5.23196 18.0096 4.58341 17.3211ZM14.5834 17.3211C13.5532 16.2275 13 15 13 13.0104C13 9.51092 15.4565 6.37372 19.0306 4.82324L19.9233 6.20085C16.588 8.00545 15.9362 10.3461 15.6756 11.8221C16.2126 11.5444 16.9156 11.4467 17.6047 11.5106C19.4091 11.6778 20.8312 13.1591 20.8312 15C20.8312 16.933 19.2642 18.5 17.3312 18.5C16.2581 18.5 15.232 18.0096 14.5834 17.3211Z"></path></svg>
                    </button>
                </td> -->
                <td class="bib-author"><?= get_author($item) ?></td>
                <td class="bib-title"><?= get_title($item) ?> 
                    <span class="source"><?= get_source($item) != false ? "— " . get_source($item) : "" ?></span>
                </td>
                <td class="bib-type"><em><?= (isset($item["type"]) ? $item["type"] : '' ) ?></em></td>
                <td class="bib-date" data-sort="<?= get_date_iso($item) ?>"><?= get_date($item) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<script defer src="<?= App::plugin('corentinbrule/citeproc')->asset('table.js') ?>"></script>

<?php echo js(['/media/plugins/corentinbrule/citeproc/table.js']); ?>