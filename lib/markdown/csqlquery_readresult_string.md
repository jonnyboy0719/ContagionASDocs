**Find the result by using the ID**
```cpp
string strExample1 = SQL::ReadResult::GetString( hQuery, 0 );
```

**Find the result by using the row name**
```cpp
string strExample2 = SQL::ReadResult::GetString( hQuery, "example" );
```
