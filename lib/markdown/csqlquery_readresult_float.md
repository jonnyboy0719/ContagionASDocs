**Find the result by using the ID**
```cpp
float flExample1 = SQL::ReadResult::GetFloat( hQuery, 0 );
```

**Find the result by using the row name**
```cpp
float flExample2 = SQL::ReadResult::GetFloat( hQuery, "example" );
```
