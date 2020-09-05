**Find the result by using the ID**
```cpp
int iExample1 = SQL::ReadResult::GetInt( hQuery, 0 );
```

**Find the result by using the row name**
```cpp
int iExample2 = SQL::ReadResult::GetInt( hQuery, "example" );
```
