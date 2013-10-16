Contact counter plugin
======================

# contact counter plugin

Compatible with Osclass version 3.1 and up.
Osclass plugin that improve upload image process.

Replace the upload process and use javascript library fine uploader and jquery.

## Features

  * Manage listing, link to show graph of contacts to a specific listing.

  * Contact listing stats, stadistics of a specific listing, filters by 10 days/weeks/months.

  * Contact site stats, stadistics of contacts in general, filters by 10 days/weeks/months.

## Screenshots

![alt text](http://i.imgur.com/UDlcZtT.png "Contact counter plugin")

![alt text](http://i.imgur.com/GUNkjJ0.png "Contact counter plugin")

![alt text](http://i.imgur.com/MkWiWg7.png "Contact counter plugin")

## How do I show the number of contacts to a particular listing?

You can display the number of contacts to a listing wherever you want.

By adding this line of code

```
<?php echo cc_contacts_by_listing( osc_item_id() ); ?>
```

## How can I show the number of contacts to a particular user?

You can display the number of contacts to a particular user wherever you want.

*(Only will return data if is a registred user, else return 0 contacts)

By adding this line of code

```
<?php echo cc_contacts_by_user( osc_user_id() ); ?>
```



