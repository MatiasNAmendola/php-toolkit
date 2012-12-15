# Pallet
A PHP ORM framework for the faint-hearted.

## How does it work?
It doesn't (yet).

## How do I use it?
You don't (yet).

### Examples
```php
<?php

class MyModel extends Model
{
	function declareFields() {
		$this->id = Fields::Key(true);
		$this->name = Fields::Text(100);
	}
}

$allObjects = MyModel::all();

```

## Notes

### Model
Model is the class from which models should inherit, it has a static method called ::all() that returns a query set for the model you're calling it on.

### QuerySet
Contains magical properties.
the filter() method will return a new query set that includes the passed in filter condition.

### Backend
An interface that the Model and QuerySet classes will use to execture their instructions on the underlying database.

### Backend Cursor 
An interface for an interable Database cursor.
