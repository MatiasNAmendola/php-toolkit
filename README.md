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
Model is the class from which models should inherit, it has a static method called ::all() that returns a QuerySet for the model you're calling it on.

### Field 
An interface that must be implemented by all Field types.

#### Fields
Convenience class that returns Field instances.

### QuerySet
Contains magical properties.
The tree of boolean conditions is stored inside an array currently, waiting on a better structure.

calling filter() with a new conditional will produce a new QuerySet that also contains the new condition.

### Backend
An interface that the Model and QuerySet classes will use to execture their instructions on the underlying database.

### Backend Cursor 
An interface for an interable Database cursor.

### Supported Backends
  * MySQL
