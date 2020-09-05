**Find the result by using the ID**
```cpp
CSQLDate@ pDateExample1 = SQL::ReadResult::GetDate( hQuery, 0 );
```

**Find the result by using the row name**
```cpp
CSQLDate@ pDateExample2 = SQL::ReadResult::GetDate( hQuery, "example" );
```
