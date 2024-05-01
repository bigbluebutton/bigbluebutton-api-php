{{#include ../header.md}}

# Documentation
(tbd)

1) Install rust

2) Install mdbook
```
cargo install mdbook
```


3) Install this to ensure external links will be opened in a new tab
   https://crates.io/crates/mdbook-external-links
```
cargo install mdbook-external-links
```

4) Install extention for alerts
   https://crates.io/crates/mdbook-alerts
```
cargo install mdbook-alerts
```

> [!CAUTION]
> ??? How to prevent the markdown below of being parsed?
> 
```markdown
> [!NOTE]  
> Highlights information that users should take into account, even when skimming.

> [!TIP]
> Optional information to help a user be more successful.

> [!IMPORTANT]  
> Crucial information necessary for users to succeed.

> [!WARNING]  
> Critical content demanding immediate user attention due to potential risks.

> [!CAUTION]
> Negative potential consequences of an action.
```

![Alerts](https://github.com/lambdalisue/rs-mdbook-alerts/blob/main/example/example.png?raw=true)

4) build the book locally
```
mdbook serve --open
```