# Preset Governance

Theme preset 不是另一套主题。

TailwindScore 的 preset 只允许在同一组件系统内产生受控变体。

## 1. Allowed Preset Axes

仅允许三类 preset variation：

- token variation
- spacing rhythm variation
- commerce mood variation

## 2. What a Preset May Change

允许：

- color token defaults
- typography emphasis
- spacing density
- radius / motion temperament
- already-governed commerce behavior defaults

## 3. What a Preset May Not Change

禁止：

- duplicated component systems
- separate theme personalities
- alternate template trees
- incompatible section composition
- one preset one markup structure

## 4. Consistency Rule

无论 preset 如何变化，都必须保持：

- same component contracts
- same content surface registry
- same SSR structure
- same accessibility baseline

Preset 只能改变调性，不能改变系统身份。

## 5. Implementation Direction

Preset 应由以下层组合而成：

1. token defaults
2. bounded behavior defaults
3. optional content tone defaults

而不是复制 PHP、复制模板、复制 CSS bundle。
