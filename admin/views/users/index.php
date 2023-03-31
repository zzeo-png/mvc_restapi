<h3>Seznam vseh uporabnikov</h3>
<a href="?controller=users&action=create"><button>Dodaj</button></a>
<table>
  <thead>
    <tr>
      <th>Uporabniško ime</th>
      <th>Ime</th>
      <th>Priimek</th>
      <th>E-Pošta</th>
      <th>Administrator</th>
    </tr>
  </thead>
  <tbody>
    <!-- tukaj se sprehodimo čez array oglasov in izpisujemo vrstico posameznega oglasa-->
    <?php foreach ($users as $user) { ?>
      <tr>
        <td><?php echo $user->username; ?></td>
        <td><?php echo $user->name; ?></td>
        <td><?php echo $user->surname; ?></td>
        <td><?php echo $user->email; ?></td>
        <td><?php echo $user->isAdmin; ?></td>
        <td>
          <!-- pri vsakem oglasu dodamo povezavo na akcije show, edit in delete, z idjem oglasa. Uporabnik lahko tako proži novo akcijo s pritiskom na gumb.-->
          <a href='?controller=users&action=edit&id=<?php echo $user->id; ?>'><button>Uredi</button></a>
          <a href='?controller=users&action=delete&id=<?php echo $user->id; ?>'><button>Izbriši</button></a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>