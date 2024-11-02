<?php

function save_json($tasks)
{
    $json = json_encode($tasks, JSON_PRETTY_PRINT);
    file_put_contents('tasks.json', $json);
}

function get_json()
{
    $json = file_get_contents('tasks.json');
    $tasks = json_decode($json, true);

    if (!is_array($tasks)) {
        $tasks = [];
    }

    return $tasks;
}




function create_task($name, $description, $date)
{

    $tasks = get_tasks();

    $task = array(
        'name' => $name,
        'description' => $description,
        'date' => $date
    );

    $tasks[] = $task;

    save_json($tasks);
}

function get_tasks()
{
    $tasks = get_json();
    return $tasks;
}

function update_task($key, $name, $description, $date)
{
    $tasks = get_tasks();

    $tasks[$key] = array(
        'name' => $name,
        'description' => $description,
        'date' => $date
    );

    save_json($tasks);
}
function delete_task($key)
{
    $tasks = get_tasks();
    array_splice($tasks, $key, 1);
    save_json($tasks);
}


if (isset($_POST['name'], $_POST['description'])) {

    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);

    if (isset($_POST['update'], $_POST['key']) &&  $_POST['update'] == '1') {
        update_task($_POST['key'], $name, $description, time());
    } else {
        create_task($name, $description, time());
    }
}

if (isset($_GET['action'], $_GET['key']) && $_GET['action'] == 'delete') {
    $key = (int) $_GET['key'];
    delete_task($key);
    echo 'Tache ' . $key . ' supprimé';
}

$current_task = null;
if (isset($_GET['action'], $_GET['key']) && $_GET['action'] == 'update') {
    $current_task = get_tasks()[$_GET['key']];
    var_dump($current_task);
}

?>

<?php if ($current_task) { ?>

    <form method="post">
        <div>
            <input type="text" name="name" value="<?php echo $current_task['name']; ?>" placeholder="nom">
        </div>
        <div>
            <input type="text" name="description" value="<?php echo $current_task['description']; ?>" placeholder=" description">
        </div>
        <div>
            <input type="hidden" name="update" value="1" />
            <input type="hidden" name="key" value="<?php echo $_GET['key']; ?>" />
            <input type="submit" />
        </div>
    </form>
<?php } else { ?>
    <form method="post">
        <div>
            <input type="text" name="name" placeholder="nom">
        </div>
        <div>
            <input type="text" name="description" placeholder="description">
        </div>
        <div>
            <input type="submit" />
        </div>
    </form>


<?php } ?>

<table>
    <tr>
        <th>
            Name
        </th>
        <th>
            Description
        </th>
        <th>
            Date
        </th>
        <th>
            Update
        </th>
        <th>
            Delete
        </th>
    </tr>
    <?php foreach (get_tasks() as $key => $task): ?>
        <tr>
            <td>
                <?php echo $task['name']; ?>
            </td>
            <td>
                <?php echo $task['description']; ?>
            </td>
            <td>
                <?php echo $task['date']; ?>
            </td>
            <td>
                <a href="?action=update&key=<?php echo $key; ?>">Modification</a>
            </td>
            <td>
                <a href="?action=delete&key=<?php echo $key; ?>">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>