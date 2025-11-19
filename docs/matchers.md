# Matchers

Once an operation is defined, you can apply matchers to respond to specific criteria.

A brief example was shown in the [quickstart](../README.md#quickstart).

You define the `type` and `args` and the mock server constructs it for you.

The `type` may be the matcher's classname, or its shorthand alias as specified below. 
They can also be viewed directly from the [Matcher Module's config](../src/Matcher/Module.php).

## All Of

Match if *all* given sub-matchers match.

|      | alias | class name                                                                     |
|:-----|:------|:-------------------------------------------------------------------------------|
| type | allOf | [\Membrane\MockServer\Matcher\Matcher\AllOf](../src/Matcher/Matcher/AllOf.php) |

| args     | type                        | description                          |
|:---------|:----------------------------|--------------------------------------|
| matchers | `list<array<string,mixed>>` | an array of configs for sub-matchers |

## Any Of

Match if *any* given sub-matchers match.

|      | alias | class name                                                                     |
|:-----|:------|:-------------------------------------------------------------------------------|
| type | anyOf | [\Membrane\MockServer\Matcher\Matcher\AnyOf](../src/Matcher/Matcher/AnyOf.php) |

| args     | type                        | description                          |
|:---------|:----------------------------|--------------------------------------|
| matchers | `list<array<string,mixed>>` | an array of configs for sub-matchers |

## Array

> [!NOTE]
> Array matchers will never match non-array fields.

### Contains

Match if the specified field *contains* all given values.

|      | alias          | class name                                                                                       |
|:-----|:---------------|:-------------------------------------------------------------------------------------------------|
| type | array.contains | [\Membrane\MockServer\Matcher\Matcher\Array\Contains](../src/Matcher/Matcher/Array/Contains.php) |

| args   | type           | description                      |
|:-------|:---------------|----------------------------------|
| field  | `list<string>` | breadcrumbs leading to the field |
| values | `list<scalar>` | values that must be in the field |

## Equals

Match if the specified field *equals* the given value.

> [!NOTE]
> This is a non-strict equality check
> i.e. the string "2" matches the integer 2.

|      | alias  | class name                                                                       |
|:-----|:-------|:---------------------------------------------------------------------------------|
| type | equals | [\Membrane\MockServer\Matcher\Matcher\Equals](../src/Matcher/Matcher/Equals.php) |

| args  | type           | description                      |
|:------|:---------------|----------------------------------|
| field | `list<string>` | breadcrumbs leading to the field |
| value | `scalar`       | value that must equal the field  |

## Exists

Match if the specified field exists and is non-null.

|      | alias  | class name                                                                       |
|:-----|:-------|:---------------------------------------------------------------------------------|
| type | exists | [\Membrane\MockServer\Matcher\Matcher\Exists](../src/Matcher/Matcher/Exists.php) |

| args  | type           | description                      |
|:------|:---------------|----------------------------------|
| field | `list<string>` | breadcrumbs leading to the field |

## Greater Than

Match if the specified field is numeric and greater than the given value.

|      | alias        | class name                                                                                 |
|:-----|:-------------|:-------------------------------------------------------------------------------------------|
| type | greater-than | [\Membrane\MockServer\Matcher\Matcher\GreaterThan](../src/Matcher/Matcher/GreaterThan.php) |

| args  | type           | description                          |
|:------|:---------------|--------------------------------------|
| field | `list<string>` | breadcrumbs leading to the field     |
| value | `float\|int`    | value the field must be greater than |

## Less Than
    
Match if the specified field is numeric and less than the given value.

|      | alias     | class name                                                                           |
|:-----|:----------|:-------------------------------------------------------------------------------------|
| type | less-than | [\Membrane\MockServer\Matcher\Matcher\LessThan](../src/Matcher/Matcher/LessThan.php) |

| args  | type           | description                       |
|:------|:---------------|-----------------------------------|
| field | `list<string>` | breadcrumbs leading to the field  |
| value | `float\|int`    | value the field must be less than |

## Not

Match if *not matched* by the given sub-matcher.

|      | alias | class name                                                                 |
|:-----|:------|:---------------------------------------------------------------------------|
| type | not   | [\Membrane\MockServer\Matcher\Matcher\Not](../src/Matcher/Matcher/Not.php) |

| args    | type                  | description                |
|:--------|:----------------------|----------------------------|
| matcher | `array<string,mixed>` | config for the sub-matcher |

## String

> [!NOTE]
> String matchers will never match non-string fields.

### Regex

Match if the specified field matches the given regular expression.

|      | alias        | class name                                                                                   |
|:-----|:-------------|:---------------------------------------------------------------------------------------------|
| type | string.regex | [\Membrane\MockServer\Matcher\Matcher\String\Regex](../src/Matcher/Matcher/String/Regex.php) |

| args    | type           | description                             |
|:--------|:---------------|-----------------------------------------|
| field   | `list<string>` | breadcrumbs leading to the field        |
| pattern | `string`       | regular expression the field must match |
